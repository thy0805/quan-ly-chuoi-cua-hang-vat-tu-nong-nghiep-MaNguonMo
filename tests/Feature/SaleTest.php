<?php

namespace Tests\Feature;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\SaleService;
use App\Support\SaleMoney;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SaleTest extends TestCase
{
    private User $admin;
    private User $user;
    private Product $first;
    private Product $second;
    private array $cookies = [];
    private array $sessions = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('mnm_inventory_test_20261021', DB::selectOne('SELECT DATABASE() AS db')->db);
        DB::beginTransaction();
        $this->admin = User::where('role', 'admin')->firstOrFail();
        $this->user = User::where('username', 'nhanvien_demo_1')->firstOrFail();
        $sample = Product::where('code', 'DEMO-01')->firstOrFail();
        foreach (['first', 'second'] as $field) {
            $this->{$field} = Product::create(['code' => 'QA-C2-'.bin2hex(random_bytes(6)), 'name' => 'Vật tư bán hàng '.$field, 'category_id' => $sample->category_id, 'unit_id' => $sample->unit_id, 'sale_price' => '12.50']);
        }
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

    private function input(array $lines = []): array
    {
        return ['client_key' => (string) Str::uuid(), 'note' => 'QA C2 🌱', 'lines' => $lines ?: [['product_id' => $this->first->id, 'quantity' => '2.125', 'unit_price' => '12.50']]];
    }

    private function stock(Product $product, string $quantity = '10'): void
    {
        app(InventoryService::class)->move($this->admin, ['product_id' => $product->id, 'direction' => 'in', 'quantity' => $quantity, 'idempotency_key' => (string) Str::uuid()]);
    }

    private function invalid(callable $call, string $field): void
    {
        try { $call(); $this->fail('Expected validation'); } catch (ValidationException $e) { $this->assertArrayHasKey($field, $e->errors()); }
    }

    private function forbidden(callable $call): void
    {
        try { $call(); $this->fail('Expected forbidden'); } catch (HttpException $e) { $this->assertSame(403, $e->getStatusCode()); }
    }

    private function financialState(): array
    {
        return array_map(fn ($table) => DB::table($table)->orderBy('id')->get()->toJson(), ['sales', 'sale_lines', 'product_stocks', 'inventory_movements']);
    }

    public function test_checked_add_rejects_before_machine_integer_overflow_and_keeps_exact_limit(): void
    {
        $this->assertSame(0, SaleMoney::add(0, 0));
        $this->assertSame(SaleMoney::MAX, SaleMoney::add(SaleMoney::MAX - 1, 1));
        $this->assertSame(SaleMoney::MAX, SaleMoney::add(SaleMoney::MAX, 0));
        foreach ([[SaleMoney::MAX, 1], [0, SaleMoney::MAX + 1], [PHP_INT_MAX, 1], [1, PHP_INT_MAX], [0, -1], [-1, 0]] as [$total, $amount]) {
            $this->invalid(fn () => SaleMoney::add($total, $amount), 'total');
        }
    }

    public function test_many_valid_lines_exceeding_total_reject_create_edit_and_http_without_partial_writes(): void
    {
        $this->stock($this->first); $this->stock($this->second);
        $products = [$this->first, $this->second];
        for ($i = 0; $i < 8; $i++) {
            $product = $this->first->replicate();
            $product->code = 'QA-C2-R-'.bin2hex(random_bytes(6));
            $product->save();
            $products[] = $product;
        }
        $lines = array_map(fn ($p) => ['product_id' => $p->id, 'quantity' => '1', 'unit_price' => '9999999999999999.99'], $products);
        foreach ($lines as $line) $this->assertSame('9999999999999999.99', SaleMoney::line($line['quantity'], $line['unit_price']));
        $data = $this->input($lines);
        $before = $this->financialState();
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, $data), 'total');
        $this->assertSame($before, $this->financialState());
        $this->assertFalse(Sale::where('client_key', $data['client_key'])->exists());
        $valid = $this->input([['product_id' => $this->first->id, 'quantity' => '1', 'unit_price' => '1']]);
        $draft = app(SaleService::class)->draft($this->user, $valid);
        $edit = [...$valid, 'lines' => $lines, 'version' => 1];
        $before = $this->financialState();
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, $edit, $draft->id), 'total');
        $this->assertSame($before, $this->financialState());
        $this->login($this->user);
        $this->browse('POST', '/sales', [...$data, '_token' => $this->app['session']->token()], true)->assertUnprocessable()->assertJsonValidationErrors('total');
        $this->assertSame($before, $this->financialState());
        $this->browse('PATCH', '/sales/'.$draft->id, [...$edit, '_token' => $this->app['session']->token()], true)->assertUnprocessable()->assertJsonValidationErrors('total');
        $this->assertSame($before, $this->financialState());
    }

    public function test_overlarge_stored_draft_confirm_rolls_back_earlier_stock_and_http_returns_validation(): void
    {
        $this->stock($this->first); $this->stock($this->second);
        $sale = app(SaleService::class)->draft($this->user, $this->input([
            ['product_id' => $this->first->id, 'quantity' => '1', 'unit_price' => '1'],
            ['product_id' => $this->second->id, 'quantity' => '1', 'unit_price' => '1'],
        ]));
        DB::table('sale_lines')->where('sale_id', $sale->id)->update(['unit_price' => '9999999999999999.99', 'line_total' => '9999999999999999.99']);
        $before = $this->financialState();
        $this->invalid(fn () => app(SaleService::class)->confirm($this->user, $sale->id, 1), 'total');
        $this->assertSame($before, $this->financialState());
        $this->login($this->user);
        $this->browse('POST', '/sales/'.$sale->id.'/confirm', ['_token' => $this->app['session']->token(), 'version' => 1], true)->assertUnprocessable()->assertJsonValidationErrors('total');
        $this->assertSame($before, $this->financialState());
        $this->assertSame('draft', $sale->fresh()->status);
    }

    public function test_exact_maximum_invoice_confirms_and_replays_without_precision_loss(): void
    {
        $this->stock($this->first);
        $sale = app(SaleService::class)->draft($this->user, $this->input([['product_id' => $this->first->id, 'quantity' => '1', 'unit_price' => '9999999999999999.99']]));
        $this->assertSame('9999999999999999.99', $sale->total);
        $confirmed = app(SaleService::class)->confirm($this->user, $sale->id, 1);
        $this->assertSame('confirmed', $confirmed->status);
        $this->assertSame('9999999999999999.99', $confirmed->total);
        $this->assertSame('9.000', $this->first->fresh()->stock->quantity);
        $before = $this->financialState();
        app(SaleService::class)->confirm($this->user, $sale->id, 1);
        $this->assertSame($before, $this->financialState());
    }

    private function browse(string $method, string $uri, array $data = [], bool $json = false)
    {
        Auth::forgetGuards();
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $this->app->forgetInstance('redirect');
        $response = $this->call($method, $uri, $data, $this->cookies, [], ['HTTP_ACCEPT' => $json ? 'application/json' : 'text/html', 'HTTP_REFERER' => url('/sales'), 'REMOTE_ADDR' => '127.0.0.1']);
        foreach ($response->headers->getCookies() as $cookie) $this->cookies[$cookie->getName()] = $cookie->getValue();
        $this->sessions[] = $this->app['session']->driver()->getId();
        return $response;
    }

    private function login(User $actor): void
    {
        $this->cookies = [];
        $this->browse('GET', '/login')->assertOk();
        $this->browse('POST', '/login', ['_token' => $this->app['session']->token(), 'username' => $actor->username, 'password' => getenv($actor->role === 'admin' ? 'MNM_DEMO_ADMIN_PASSWORD' : 'MNM_DEMO_USER_PASSWORD')])->assertRedirect();
    }

    public function test_money_exact_rounding_limits_and_mysql_decimal_oracle(): void
    {
        $this->assertSame('26.56', SaleMoney::line('2.125', '12.50'));
        $this->assertSame('0.01', SaleMoney::line('0.5', '0.01'));
        $this->assertSame('0.00', SaleMoney::line('0.499', '0.01'));
        $this->assertSame('9999999999999999.99', SaleMoney::line('1', '9999999999999999.99'));
        $this->assertSame('9999999999999.90', SaleMoney::line('0.001', '9999999999999900.00'));
        $this->invalid(fn () => SaleMoney::line('2', '9999999999999999.99'), 'total');
        foreach ([0.1, '-1', '1e2', '1.001', '10000000000000000'] as $bad) $this->invalid(fn () => SaleMoney::cents($bad), 'unit_price');
        for ($i = 0; $i < 80; $i++) {
            $q = random_int(1, 999).'.'.str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $p = random_int(0, 99999).'.'.str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
            $expected = DB::selectOne('SELECT CAST(ROUND(CAST(? AS DECIMAL(15,3))*CAST(? AS DECIMAL(18,2)),2) AS CHAR) AS amount', [$q, $p])->amount;
            $this->assertSame($expected, SaleMoney::line($q, $p));
        }
    }

    public function test_draft_many_lines_has_server_total_and_does_not_touch_stock(): void
    {
        $this->stock($this->first);
        $input = $this->input([['product_id' => $this->first->id, 'quantity' => '2.125', 'unit_price' => '12.50'], ['product_id' => $this->second->id, 'quantity' => '0.5', 'unit_price' => '0.01']]);
        $sale = app(SaleService::class)->draft($this->user, [...$input, 'total' => '0', 'user_id' => $this->admin->id, 'status' => 'confirmed']);
        $this->assertSame('26.57', $sale->total);
        $this->assertSame('draft', $sale->status);
        $this->assertNull($sale->confirmed_at);
        $this->assertSame($this->user->id, $sale->user_id);
        $this->assertCount(2, $sale->lines);
        $this->assertSame('10.000', $this->first->fresh()->stock->quantity);
        $this->assertNull($this->second->fresh()->stock);
    }

    public function test_draft_create_replay_normalizes_payload_and_rejects_conflict(): void
    {
        $input = $this->input();
        $sale = app(SaleService::class)->draft($this->user, $input);
        $again = $input;
        $again['lines'][0]['quantity'] = '2.125';
        $again['lines'][0]['unit_price'] = '12.5';
        $this->assertSame($sale->id, app(SaleService::class)->draft($this->user, $again)->id);
        $this->assertCount(1, $sale->fresh()->lines);
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, [...$input, 'note' => 'Changed']), 'client_key');
        $this->forbidden(fn () => app(SaleService::class)->draft($this->admin, $input));
    }

    public function test_user_confirms_many_lines_atomically_with_unique_line_sources_and_replay(): void
    {
        $this->stock($this->first); $this->stock($this->second);
        $sale = app(SaleService::class)->draft($this->user, $this->input([['product_id' => $this->second->id, 'quantity' => '1.5', 'unit_price' => '20'], ['product_id' => $this->first->id, 'quantity' => '2', 'unit_price' => '12.5']]));
        $done = app(SaleService::class)->confirm($this->user, $sale->id, $sale->version);
        $this->assertSame('confirmed', $done->status);
        $this->assertSame('55.00', $done->total);
        $this->assertNotNull($done->confirmed_at);
        $this->assertSame('8.000', $this->first->fresh()->stock->quantity);
        $this->assertSame('8.500', $this->second->fresh()->stock->quantity);
        $ids = $done->lines->pluck('id')->sort()->values()->all();
        $movements = InventoryMovement::where('source_type', 'sale')->whereIn('source_id', $ids)->get();
        $this->assertCount(2, $movements);
        $this->assertSame($ids, $movements->pluck('source_id')->sort()->values()->all());
        foreach ($movements as $movement) { $this->assertSame('out', $movement->direction); $this->assertSame($this->user->id, $movement->user_id); }
        app(SaleService::class)->confirm($this->user, $sale->id, $sale->version);
        $this->assertSame(2, InventoryMovement::where('source_type', 'sale')->whereIn('source_id', $ids)->count());
        $this->assertSame('8.000', $this->first->fresh()->stock->quantity);
    }

    public function test_one_insufficient_line_rolls_back_all_other_lines(): void
    {
        $this->stock($this->first); $this->stock($this->second, '1');
        $sale = app(SaleService::class)->draft($this->user, $this->input([['product_id' => $this->first->id, 'quantity' => '2', 'unit_price' => '1'], ['product_id' => $this->second->id, 'quantity' => '2', 'unit_price' => '1']]));
        $this->invalid(fn () => app(SaleService::class)->confirm($this->user, $sale->id, 1), 'quantity');
        $this->assertSame('draft', $sale->fresh()->status);
        $this->assertSame('10.000', $this->first->fresh()->stock->quantity);
        $this->assertSame('1.000', $this->second->fresh()->stock->quantity);
        $this->assertSame(0, InventoryMovement::where('source_type', 'sale')->whereIn('source_id', $sale->lines->pluck('id'))->count());
    }

    public function test_exception_after_stock_write_rolls_back_entire_invoice(): void
    {
        $this->stock($this->first); $this->stock($this->second);
        $sale = app(SaleService::class)->draft($this->user, $this->input([['product_id' => $this->first->id, 'quantity' => '1', 'unit_price' => '1'], ['product_id' => $this->second->id, 'quantity' => '1', 'unit_price' => '1']]));
        InventoryMovement::creating(function ($movement) { if ($movement->source_type === 'sale' && $movement->product_id === $this->second->id) throw new \RuntimeException('Injected C2 write failure'); });
        try { app(SaleService::class)->confirm($this->user, $sale->id, 1); $this->fail('Expected injected error'); } catch (\RuntimeException $e) { $this->assertSame('Injected C2 write failure', $e->getMessage()); } finally { InventoryMovement::flushEventListeners(); }
        $this->assertSame('draft', $sale->fresh()->status);
        $this->assertSame('10.000', $this->first->fresh()->stock->quantity);
        $this->assertSame('10.000', $this->second->fresh()->stock->quantity);
        $this->assertSame(0, InventoryMovement::where('source_type', 'sale')->whereIn('source_id', $sale->lines->pluck('id'))->count());
    }

    public function test_draft_edit_and_stale_version_never_confirms_changed_content(): void
    {
        $this->stock($this->first);
        $data = $this->input();
        $sale = app(SaleService::class)->draft($this->user, $data);
        $data['lines'][0]['quantity'] = '3';
        $updated = app(SaleService::class)->draft($this->user, [...$data, 'version' => 1], $sale->id);
        $this->assertSame(2, $updated->version);
        $this->assertSame('37.50', $updated->total);
        $this->invalid(fn () => app(SaleService::class)->confirm($this->user, $sale->id, 1), 'version');
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, [...$data, 'version' => 1], $sale->id), 'version');
        $this->forbidden(fn () => app(SaleService::class)->confirm($this->admin, $sale->id, 2));
        app(SaleService::class)->confirm($this->user, $sale->id, 2);
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, [...$data, 'version' => 2], $sale->id), 'sale');
        $this->assertSame('7.000', $this->first->fresh()->stock->quantity);
    }

    public function test_input_validation_empty_duplicate_inactive_and_invalid_price(): void
    {
        $data = $this->input();
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, [...$data, 'lines' => []]), 'lines');
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, [...$data, 'lines' => [$data['lines'][0],$data['lines'][0]]]), 'lines.0.product_id');
        foreach (['quantity' => '0', 'unit_price' => '1.001', 'product_id' => '9223372036854775808'] as $field => $value) {
            $bad = $data; $bad['lines'][0][$field] = $value;
            $this->invalid(fn () => app(SaleService::class)->draft($this->user, $bad), 'lines.0.'.$field);
        }
        $this->first->update(['is_active' => false]);
        $this->invalid(fn () => app(SaleService::class)->draft($this->user, $data), 'lines.0.product_id');
    }

    public function test_inactive_actor_product_and_manual_inventory_permission(): void
    {
        $this->stock($this->first);
        $sale = app(SaleService::class)->draft($this->user, $this->input());
        DB::table('users')->where('id', $this->user->id)->update(['is_active' => 0]);
        $this->forbidden(fn () => app(SaleService::class)->confirm($this->user, $sale->id, 1));
        DB::table('users')->where('id', $this->user->id)->update(['is_active' => 1]);
        $this->first->update(['is_active' => false]);
        $this->invalid(fn () => app(SaleService::class)->confirm($this->user, $sale->id, 1), 'product_id');
        $this->user->role = 'admin';
        $this->forbidden(fn () => app(InventoryService::class)->move($this->user, ['product_id' => $this->first->id, 'quantity' => '1', 'direction' => 'in', 'idempotency_key' => (string) Str::uuid()]));
    }

    public function test_confirmed_header_lines_and_references_are_protected_in_mysql(): void
    {
        $this->stock($this->first);
        $sale = app(SaleService::class)->draft($this->user, $this->input());
        $sale = app(SaleService::class)->confirm($this->user, $sale->id, 1);
        $line = $sale->lines->first();
        foreach ([fn () => DB::table('sales')->where('id', $sale->id)->update(['note' => 'tamper']), fn () => DB::table('sales')->where('id', $sale->id)->delete(), fn () => DB::table('sale_lines')->where('id', $line->id)->update(['quantity' => '1']), fn () => DB::table('sale_lines')->where('id', $line->id)->delete(), fn () => DB::table('sale_lines')->insert([...$line->getAttributes(), 'id' => null])] as $call) {
            try { $call(); $this->fail('Expected immutable MySQL trigger'); } catch (QueryException $e) { $this->assertSame(1644, $e->errorInfo[1]); }
        }
        $this->invalid(fn () => $sale->update(['note' => 'tamper']), 'sale');
        $this->invalid(fn () => $line->update(['unit_price' => '0']), 'sale');
        try { DB::table('users')->where('id', $this->user->id)->delete(); $this->fail('Expected FK'); } catch (QueryException $e) { $this->assertSame(1451, $e->errorInfo[1]); }
        $this->assertSame($line->id, $line->fresh()->id);
    }

    public function test_product_snapshot_survives_rename_and_confirmation_replay(): void
    {
        $this->stock($this->first);
        $name = $this->first->name;
        $sale = app(SaleService::class)->draft($this->user, $this->input());
        $this->first->update(['name' => 'Tên mới sau lập phiếu', 'sale_price' => '900']);
        app(SaleService::class)->confirm($this->user, $sale->id, 1);
        $this->assertSame($name, $sale->fresh()->lines->first()->product_name);
        $this->assertSame('12.50', $sale->fresh()->lines->first()->unit_price);
    }

    public function test_http_session_csrf_draft_validation_confirm_print_and_filters(): void
    {
        $this->stock($this->first);
        $this->browse('GET','/sales')->assertRedirect('/login');
        $this->login($this->user);
        $this->browse('GET','/sales/create')->assertOk()->assertSee('Lưu nháp và tính tổng')->assertSee('sales.js');
        $data = $this->input();
        $this->browse('POST','/sales',$data,true)->assertStatus(419);
        $this->browse('GET','/sales/create')->assertOk();
        $data['_token'] = $this->app['session']->token();
        $this->browse('POST','/sales',[...$data,'lines'=>[]],true)->assertUnprocessable();
        $response = $this->browse('POST','/sales',$data,true)->assertOk()->assertJsonPath('data.total','26.56')->assertJsonPath('data.status','draft');
        $id = $response->json('data.id');
        $this->browse('GET','/sales/'.$id)->assertOk()->assertSee('Sửa nháp');
        $this->browse('GET','/sales/'.$id.'/edit')->assertOk();
        $this->browse('GET','/sales/'.$id.'/print',[],true)->assertStatus(409);
        $this->browse('GET','/sales/'.$id)->assertOk();
        $this->browse('POST','/sales/'.$id.'/confirm',['_token'=>$this->app['session']->token(),'version'=>1],true)->assertOk()->assertJsonPath('data.status','confirmed');
        $this->browse('POST','/sales/'.$id.'/confirm',['_token'=>$this->app['session']->token(),'version'=>1],true)->assertOk();
        $this->browse('GET','/sales/'.$id.'/print')->assertOk()->assertSee('Hóa đơn nội bộ')->assertSee('26.56')->assertSee('sales-print.css');
        $this->browse('GET','/sales?status=confirmed&q='.$this->first->code.'&per_page=1',[],true)->assertOk()->assertJsonPath('total',1);
        $number = Sale::findOrFail($id)->number;
        $this->browse('GET','/sales?q='.$number,[],true)->assertOk()->assertJsonPath('total',1);
        $this->browse('GET','/sales?from=2026-12-31&to=2026-01-01',[],true)->assertUnprocessable();
        $this->browse('GET','/sales?per_page=51',[],true)->assertUnprocessable();
        $this->browse('GET','/sales?to=2099-01-01',[],true)->assertOk();
        $this->browse('GET','/inventory/in')->assertForbidden();
    }

    public function test_http_idor_owner_mutation_and_admin_visibility(): void
    {
        $mine = app(SaleService::class)->draft($this->user,$this->input());
        $other = app(SaleService::class)->draft($this->admin,$this->input());
        $this->login($this->user);
        foreach (['','/edit','/print'] as $suffix) $this->browse('GET','/sales/'.$other->id.$suffix,[],true)->assertNotFound();
        $this->browse('GET','/sales?status=draft',[],true)->assertOk();
        $rows = $this->browse('GET','/sales?status=draft',[],true)->json('data');
        foreach ($rows as $row) $this->assertSame($this->user->id,$row['user_id']);
        $this->login($this->admin);
        $this->browse('GET','/sales/'.$mine->id)->assertOk();
        $this->browse('GET','/sales/'.$mine->id.'/edit',[],true)->assertForbidden();
        $this->browse('POST','/sales/'.$mine->id.'/confirm',['_token'=>$this->app['session']->token(),'version'=>1],true)->assertForbidden();
        $this->browse('GET','/users')->assertOk();
    }
}
