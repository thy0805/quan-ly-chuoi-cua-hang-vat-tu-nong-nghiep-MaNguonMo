<?php

namespace Tests\Feature;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Services\CatalogDeletion;
use App\Services\InventoryService;
use App\Support\InventoryTestProfile;
use App\Support\RuntimeIsolation;
use App\Support\StockQuantity;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    private User $admin;
    private User $user;
    private Product $product;
    private array $cookies = [];
    private array $sessions = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(InventoryTestProfile::DATABASE, DB::selectOne('SELECT DATABASE() AS db')->db);
        $this->assertSame('mnm_c1_test_runtime', config('database.connections.mysql.username'));
        DB::beginTransaction();
        $this->admin = User::where('role', 'admin')->firstOrFail();
        $this->user = User::where('username', 'nhanvien_demo_1')->firstOrFail();
        $sample = Product::where('code', 'DEMO-01')->firstOrFail();
        $this->product = Product::create(['code' => 'QA-C1-UNIT-'.bin2hex(random_bytes(5)), 'name' => 'Vật tư kiểm thử độc lập 🌱', 'category_id' => $sample->category_id, 'unit_id' => $sample->unit_id, 'sale_price' => '1.00']);
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) DB::rollBack();
        Auth::forgetGuards();
        foreach (array_unique($this->sessions) as $id) {
            if (preg_match('/^[A-Za-z0-9]{40}$/D', $id) && is_file($path = config('session.files').'/'.$id)) unlink($path);
        }
        parent::tearDown();
    }

    private function data(string $quantity = '20', string $direction = 'in'): array
    {
        return ['product_id' => $this->product->id, 'direction' => $direction, 'quantity' => $quantity, 'note' => 'Kiểm thử kho 🌱', 'idempotency_key' => (string) Str::uuid()];
    }

    private function move(array $data): InventoryMovement
    {
        return app(InventoryService::class)->move($this->admin, $data);
    }

    private function invalid(callable $operation, string $field): void
    {
        try {
            $operation();
            $this->fail('Expected validation rejection');
        } catch (ValidationException $error) {
            $this->assertArrayHasKey($field, $error->errors());
        }
    }

    private function browse(string $method, string $uri, array $data = [], bool $json = false)
    {
        Auth::forgetGuards();
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $this->app->forgetInstance('redirect');
        $response = $this->call($method, $uri, $data, $this->cookies, [], ['HTTP_ACCEPT' => $json ? 'application/json' : 'text/html', 'HTTP_REFERER' => url('/inventory/in'), 'REMOTE_ADDR' => '127.0.0.1']);
        foreach ($response->headers->getCookies() as $cookie) $this->cookies[$cookie->getName()] = $cookie->getValue();
        $this->sessions[] = $this->app['session']->driver()->getId();

        return $response;
    }

    private function login(bool $admin = true): void
    {
        $this->browse('GET', '/login')->assertOk();
        $this->browse('POST', '/login', ['_token' => $this->app['session']->token(), 'username' => $admin ? $this->admin->username : $this->user->username, 'password' => getenv($admin ? 'MNM_DEMO_ADMIN_PASSWORD' : 'MNM_DEMO_USER_PASSWORD')])->assertRedirect();
    }

    public function test_zero_stock_read_does_not_create_rows(): void
    {
        $this->assertNull($this->product->stock);
        $this->login(false);
        $this->browse('GET', '/inventory?stock_status=zero&product_id='.$this->product->id)->assertOk()->assertSee('0.000')->assertSee($this->product->name);
        $this->assertSame(0, $this->product->stock()->count());
        $this->assertSame(0, $this->product->inventoryMovements()->count());
    }

    public function test_inventory_schema_has_unique_product_decimal_constraints_and_immutable_triggers(): void
    {
        $tables = DB::select('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() ORDER BY TABLE_NAME');
        $this->assertSame(['inventory_movements', 'migrations', 'product_categories', 'product_stocks', 'products', 'sale_lines', 'sales', 'units', 'users'], array_column($tables, 'TABLE_NAME'));
        $columns = DB::select("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME IN ('inventory_movements','product_stocks') AND COLUMN_NAME IN ('id','product_id','user_id')");
        foreach ($columns as $column) $this->assertSame('bigint', $column->COLUMN_TYPE);
        $quantities = DB::select("SELECT NUMERIC_PRECISION,NUMERIC_SCALE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME IN ('inventory_movements','product_stocks') AND COLUMN_NAME IN ('quantity','quantity_before','quantity_after')");
        $this->assertCount(4, $quantities);
        foreach ($quantities as $quantity) $this->assertSame([15, 3], [$quantity->NUMERIC_PRECISION, $quantity->NUMERIC_SCALE]);
        $constraints = DB::select("SELECT DELETE_RULE,UPDATE_RULE FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME IN ('inventory_movements','product_stocks')");
        $this->assertCount(3, $constraints);
        foreach ($constraints as $constraint) $this->assertSame(['RESTRICT', 'RESTRICT'], [$constraint->DELETE_RULE, $constraint->UPDATE_RULE]);
        $this->assertSame(7, DB::table('migrations')->count());
        $this->move($this->data());
        try { ProductStock::create(['product_id' => $this->product->id, 'quantity' => '1.000', 'updated_at' => now()]); $this->fail('Expected one stock/product'); } catch (QueryException $error) { $this->assertSame(1062, $error->errorInfo[1]); }
    }

    public function test_in_twenty_out_five_and_history_chain(): void
    {
        $in = $this->move($this->data());
        $out = $this->move($this->data('5', 'out'));
        $this->assertSame(['0.000', '20.000', '20.000', '15.000'], [$in->quantity_before, $in->quantity_after, $out->quantity_before, $out->quantity_after]);
        $this->assertSame('15.000', $this->product->fresh()->stock->quantity);
        $this->assertSame(2, $this->product->inventoryMovements()->count());
        $this->assertSame(2, $this->admin->inventoryMovements()->where('product_id', $this->product->id)->count());
        $this->assertSame($this->product->id, $out->product->id);
    }

    public function test_fractional_quantities_and_maximum_without_float(): void
    {
        $this->move($this->data('0.1'));
        $this->move($this->data('0.2'));
        $this->assertSame('0.300', $this->product->fresh()->stock->quantity);
        $this->move($this->data('0.001', 'out'));
        $this->assertSame('0.299', $this->product->fresh()->stock->quantity);
        $this->assertSame(StockQuantity::MAX, StockQuantity::milli('999999999999.999'));
        $this->assertSame('999999999999.999', StockQuantity::decimal(StockQuantity::MAX));
        $this->move($this->data('999999999999.700'));
        $this->assertSame('999999999999.999', $this->product->fresh()->stock->quantity);
        $this->invalid(fn () => $this->move($this->data('0.001')), 'quantity');
        $this->assertSame(4, $this->product->inventoryMovements()->count());
    }

    public function test_invalid_quantities_and_oversell_leave_no_state(): void
    {
        foreach (['0', '-1', '1.0001', '1e2', '1,5', '1000000000000', [], 0.1] as $quantity) {
            $this->invalid(fn () => $this->move([...$this->data(), 'quantity' => $quantity]), 'quantity');
        }
        $this->invalid(fn () => $this->move($this->data('1', 'out')), 'quantity');
        $this->assertSame(0, $this->product->stock()->count());
        $this->assertSame(0, $this->product->inventoryMovements()->count());
        $this->move($this->data('1'));
        $this->invalid(fn () => $this->move($this->data('1.001', 'out')), 'quantity');
        $this->move($this->data('1', 'out'));
        $this->assertSame('0.000', $this->product->fresh()->stock->quantity);
    }

    public function test_invalid_inactive_product_and_stale_service_actor_are_blocked(): void
    {
        $this->invalid(fn () => $this->move([...$this->data(), 'product_id' => '9223372036854775807']), 'product_id');
        $this->product->update(['is_active' => false]);
        $this->invalid(fn () => $this->move($this->data()), 'product_id');
        $this->user->role = 'admin';
        try {
            app(InventoryService::class)->move($this->user, $this->data());
            $this->fail('Expected service role check');
        } catch (HttpException $error) {
            $this->assertSame(403, $error->getStatusCode());
        }
        $this->assertSame(0, $this->product->inventoryMovements()->count());
    }

    public function test_replay_returns_same_movement_and_conflicting_payload_is_rejected(): void
    {
        $data = [...$this->data('1.1'), 'note' => '0'];
        $first = $this->move($data);
        $this->assertSame('0', $first->note);
        $replay = $this->move([...$data, 'quantity' => '1.100']);
        $this->assertSame($first->id, $replay->id);
        foreach (['quantity' => '2', 'note' => 'Khác', 'direction' => 'out', 'product_id' => Product::where('code', 'DEMO-02')->firstOrFail()->id] as $key => $value) {
            $this->invalid(fn () => $this->move([...$data, $key => $value]), 'idempotency_key');
        }
        $this->assertSame('1.100', $this->product->fresh()->stock->quantity);
        $this->assertSame(1, $this->product->inventoryMovements()->count());
    }

    public function test_failure_during_history_insert_rolls_back_new_and_existing_stock(): void
    {
        foreach ([false, true] as $existing) {
            if ($existing) $this->move($this->data('2'));
            $before = [$this->product->stock()->count(), $this->product->inventoryMovements()->count(), $this->product->fresh()->stock?->quantity];
            $dispatcher = InventoryMovement::getEventDispatcher();
            InventoryMovement::setEventDispatcher(clone $dispatcher);
            InventoryMovement::creating(fn () => throw new \RuntimeException('Injected history insert failure'));
            try {
                $this->move($this->data());
                $this->fail('Expected rollback');
            } catch (\RuntimeException $error) {
                $this->assertSame('Injected history insert failure', $error->getMessage());
            } finally {
                InventoryMovement::setEventDispatcher($dispatcher);
            }
            $this->assertSame($before, [$this->product->stock()->count(), $this->product->inventoryMovements()->count(), $this->product->fresh()->stock?->quantity]);
        }
    }

    public function test_immutable_history_sql_constraints_and_product_delete_restrict(): void
    {
        $movement = $this->move($this->data());
        foreach ([fn () => $movement->update(['note' => 'Changed']), fn () => $movement->delete()] as $operation) {
            try { $operation(); $this->fail('Expected immutable model'); } catch (\LogicException) { $this->assertTrue(true); }
        }
        foreach ([fn () => DB::table('inventory_movements')->where('id', $movement->id)->update(['note' => 'Changed']), fn () => DB::table('inventory_movements')->where('id', $movement->id)->delete()] as $operation) {
            try { $operation(); $this->fail('Expected immutable trigger'); } catch (QueryException $error) { $this->assertSame('45000', $error->errorInfo[0]); }
        }
        $this->invalid(fn () => CatalogDeletion::delete($this->product), 'delete');
        $this->assertSame(1, $this->product->inventoryMovements()->count());
        $this->assertSame('20.000', $this->product->fresh()->stock->quantity);
        $otherUnit = \App\Models\Unit::where('id', '<>', $this->product->unit_id)->firstOrFail();
        $this->invalid(fn () => $this->product->update(['unit_id' => $otherUnit->id]), 'unit_id');
        try { DB::table('product_stocks')->where('product_id', $this->product->id)->update(['quantity' => '-1']); $this->fail('Expected quantity CHECK'); } catch (QueryException $error) { $this->assertSame(3819, $error->errorInfo[1]); }
    }

    public function test_user_reads_but_cannot_open_or_submit_inventory_forms(): void
    {
        $this->login(false);
        $this->browse('GET', '/inventory')->assertOk()->assertDontSee('Xác nhận nhập kho');
        $this->browse('GET', '/inventory/history')->assertOk();
        foreach (['in', 'out'] as $direction) {
            $this->browse('GET', '/inventory/'.$direction)->assertForbidden();
            $data = $this->data();
            unset($data['direction']);
            $this->browse('POST', '/inventory/'.$direction, [...$data, '_token' => $this->app['session']->token()], true)->assertForbidden();
        }
        $this->assertSame(0, $this->product->inventoryMovements()->count());
    }

    public function test_admin_http_forms_csrf_validation_replay_and_history_filters(): void
    {
        $this->browse('GET', '/inventory')->assertRedirect('/login');
        $this->login();
        $this->browse('GET', '/inventory/in')->assertOk()->assertSee('idempotency_key')->assertSee('Xác nhận nhập kho');
        $data = $this->data();
        unset($data['direction']);
        $this->browse('POST', '/inventory/in', $data, true)->assertStatus(419);
        $data['_token'] = $this->app['session']->token();
        $this->browse('POST', '/inventory/in', [...$data, 'quantity' => '-1'], true)->assertUnprocessable()->assertJsonValidationErrors('quantity');
        $this->browse('POST', '/inventory/in', [...$data, 'user_id' => $this->user->id], true)->assertUnprocessable();
        $this->browse('POST', '/inventory/in', $data, true)->assertOk()->assertJsonPath('data.quantity_after', '20.000');
        $this->browse('POST', '/inventory/in', $data, true)->assertOk();
        $out = $this->data('5', 'out');
        unset($out['direction']);
        $this->browse('POST', '/inventory/out', [...$out, '_token' => $this->app['session']->token()])->assertRedirect();
        $this->browse('GET', '/inventory/history?product_id='.$this->product->id.'&direction=out&per_page=1', [], true)->assertOk()->assertJsonPath('total', 1)->assertJsonPath('data.0.quantity_after', '15.000');
        $this->browse('GET', '/inventory?stock_status=positive&q='.$this->product->code, [], true)->assertOk()->assertJsonPath('total', 1)->assertJsonPath('data.0.stock.quantity', '15.000');
        $this->browse('GET', '/inventory/history?from=2026-12-31&to=2026-01-01', [], true)->assertUnprocessable();
        $this->browse('GET', '/inventory/history?to=2099-01-01', [], true)->assertOk();
        $this->browse('GET', '/inventory/history?q=KHONG-TON-TAI')->assertOk()->assertSee('Chưa có giao dịch phù hợp');
        $this->browse('GET', '/inventory/history?per_page=1')->assertOk()->assertSee('Trang sau');
        $this->browse('PATCH', '/inventory/history/1', ['_token' => $this->app['session']->token()], true)->assertNotFound();
        $this->browse('GET', '/inventory')->assertOk();
        $this->browse('DELETE', '/products/'.$this->product->id, ['_token' => $this->app['session']->token()], true)->assertUnprocessable();
        $this->assertSame(2, $this->product->inventoryMovements()->count());
    }

    public function test_movement_source_is_reusable_and_manual_http_cannot_spoof_source(): void
    {
        $movement = $this->move([...$this->data(), 'source_type' => 'purchase', 'source_id' => '42-line-1']);
        $this->assertSame('purchase', $movement->source_type);
        $this->assertSame('42-line-1', $movement->source_id);
        $this->invalid(fn () => $this->move([...$this->data(), 'source_type' => 'purchase', 'source_id' => '42-line-1']), 'source_id');
        $this->invalid(fn () => $this->move([...$this->data(), 'source_type' => 'sale']), 'source_id');
        $this->login();
        $data = $this->data();
        unset($data['direction']);
        $this->browse('POST', '/inventory/in', [...$data, '_token' => $this->app['session']->token(), 'source_type' => 'purchase', 'source_id' => '42'], true)->assertUnprocessable();
    }

    public function test_isolation_profile_does_not_allow_business_or_other_accounts(): void
    {
        foreach (['database' => 'mnm_vat_tu_nong_nghiep', 'username' => 'mnm_runtime_20261017', 'host' => '192.0.2.1', 'username_root' => 'root'] as $key => $value) {
            $key = $key === 'username_root' ? 'username' : $key;
            try { RuntimeIsolation::assertDatabaseConfiguration(array_replace(config('database.connections.mysql'), [$key => $value])); $this->fail('Expected profile rejection'); } catch (\LogicException) { $this->assertTrue(true); }
        }
    }
}
