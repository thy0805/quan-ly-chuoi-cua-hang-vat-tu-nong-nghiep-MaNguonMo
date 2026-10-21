<?php

namespace Tests\Feature;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Services\PurchaseService;
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

class PurchaseTest extends TestCase
{
    private Supplier $supplier;
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
        $this->supplier = Supplier::create(['code'=>'QA-C3-'.bin2hex(random_bytes(6)), 'name'=>'Nhà cung cấp thử 🌱', 'phone'=>'0900000000']);
        $sample = Product::where('code', 'DEMO-01')->firstOrFail();
        foreach (['first', 'second'] as $field) {
            $this->{$field} = Product::create(['code' => 'QA-C3-'.bin2hex(random_bytes(6)), 'name' => 'Vật tư bán hàng '.$field, 'category_id' => $sample->category_id, 'unit_id' => $sample->unit_id, 'sale_price' => '12.50']);
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
        return ['supplier_id' => $this->supplier->id, 'client_key' => (string) Str::uuid(), 'note' => 'QA C2 🌱', 'lines' => $lines ?: [['product_id' => $this->first->id, 'quantity' => '2.125', 'unit_price' => '12.50']]];
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
        return array_map(fn ($table) => DB::table($table)->orderBy('id')->get()->toJson(), ['suppliers', 'purchases', 'purchase_lines', 'sales', 'sale_lines', 'product_stocks', 'inventory_movements']);
    }

    private function browse(string $method, string $uri, array $data = [], bool $json = false)
    {
        Auth::forgetGuards();
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $this->app->forgetInstance('redirect');
        $response = $this->call($method, $uri, $data, $this->cookies, [], ['HTTP_ACCEPT' => $json ? 'application/json' : 'text/html', 'HTTP_REFERER' => url('/purchases'), 'REMOTE_ADDR' => '127.0.0.1']);
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


    public function test_draft_replay_edit_and_stale_version_have_no_inventory_effect(): void
    {
        $data = $this->input();
        $service = app(PurchaseService::class);
        $p = $service->draft($this->admin, [...$data, 'total'=>'0', 'status'=>'confirmed', 'user_id'=>$this->user->id]);
        $this->assertSame('26.56', $p->total);
        $this->assertSame($this->admin->id, $p->user_id);
        $this->assertNull($this->first->fresh()->stock);
        $again = $data; $again['lines'][0]['unit_price']='12.5';
        $this->assertSame($p->id,$service->draft($this->admin,$again)->id);
        $this->invalid(fn () => $service->draft($this->admin,[...$data,'note'=>'khác']), 'client_key');
        $edit = [...$data,'version'=>1];$edit['lines'][0]['quantity']='3';
        $p = $service->draft($this->admin,$edit,$p->id);
        $this->assertSame(2,$p->version);$this->assertSame('37.50',$p->total);
        $this->invalid(fn () => $service->draft($this->admin,$edit,$p->id),'version');
        $this->invalid(fn () => $service->confirm($this->admin,$p->id,1),'version');
        $this->assertNull($this->first->fresh()->stock);
        $p = $service->confirm($this->admin,$p->id,2);
        $this->assertSame('3.000',$this->first->fresh()->stock->quantity);
        $state=$this->financialState();$service->confirm($this->admin,$p->id,2);
        $this->assertSame($state,$this->financialState());
        $this->invalid(fn () => $service->draft($this->admin,[...$edit,'version'=>2],$p->id),'purchase');
    }

    public function test_supplier_purchase_stock_sale_chain_and_line_source_are_exact(): void
    {
        $rows=[['product_id'=>$this->first->id,'quantity'=>'20','unit_price'=>'12500'],['product_id'=>$this->second->id,'quantity'=>'8.5','unit_price'=>'20000']];
        $p=app(PurchaseService::class)->draft($this->admin,$this->input($rows));
        $this->assertSame('420000.00',$p->total);
        $p=app(PurchaseService::class)->confirm($this->admin,$p->id,1);
        $this->assertSame('20.000',$this->first->fresh()->stock->quantity);
        $sale=app(SaleService::class)->draft($this->user,['client_key'=>(string)Str::uuid(),'lines'=>[['product_id'=>$this->first->id,'quantity'=>'2','unit_price'=>'15000'],['product_id'=>$this->second->id,'quantity'=>'1.5','unit_price'=>'25000']]]);
        $sale=app(SaleService::class)->confirm($this->user,$sale->id,1);
        $this->assertSame('67500.00',$sale->total);
        foreach ([[$this->first,'18.000'],[$this->second,'7.000']] as [$product,$final]) {
            $moves=InventoryMovement::where('product_id',$product->id)->orderBy('id')->get();
            $this->assertCount(2,$moves);
            $this->assertSame('purchase',$moves[0]->source_type);$this->assertSame('in',$moves[0]->direction);
            $this->assertSame($p->lines->firstWhere('product_id',$product->id)->id,$moves[0]->source_id);
            $this->assertSame('sale',$moves[1]->source_type);$this->assertSame('out',$moves[1]->direction);
            $this->assertSame($moves[0]->quantity_after,$moves[1]->quantity_before);
            $this->assertSame($final,$moves[1]->quantity_after);$this->assertSame($final,$product->fresh()->stock->quantity);
        }
        $state=$this->financialState();app(PurchaseService::class)->confirm($this->admin,$p->id,1);app(SaleService::class)->confirm($this->user,$sale->id,1);
        $this->assertSame($state,$this->financialState());
    }

    public function test_later_line_overflow_rolls_back_entire_purchase(): void
    {
        $this->stock($this->second,'999999999999.999');
        $p=app(PurchaseService::class)->draft($this->admin,$this->input([['product_id'=>$this->first->id,'quantity'=>'1','unit_price'=>'1'],['product_id'=>$this->second->id,'quantity'=>'0.001','unit_price'=>'1']]));
        $state=$this->financialState();
        $this->invalid(fn () => app(PurchaseService::class)->confirm($this->admin,$p->id,1),'quantity');
        $this->assertSame($state,$this->financialState());$this->assertSame('draft',$p->fresh()->status);
        $this->assertNull($this->first->fresh()->stock);
    }

    public function test_exception_after_movement_write_rolls_back_everything(): void
    {
        $p=app(PurchaseService::class)->draft($this->admin,$this->input());$state=$this->financialState();
        InventoryMovement::created(static function ($m) { if ($m->source_type==='purchase') throw new \RuntimeException('injected C3 rollback'); });
        try {
            try { app(PurchaseService::class)->confirm($this->admin,$p->id,1);$this->fail('Expected injected failure'); } catch (\RuntimeException $e) { $this->assertSame('injected C3 rollback',$e->getMessage()); }
        } finally { InventoryMovement::flushEventListeners(); }
        $this->assertSame($state,$this->financialState());
    }

    public function test_invalid_quantities_prices_duplicate_products_and_suppliers(): void
    {
        $service=app(PurchaseService::class);$data=$this->input();
        foreach (['0','-1','1e2','0.0001','1000000000000'] as $q) { $bad=$data;$bad['lines'][0]['quantity']=$q;$this->invalid(fn () => $service->draft($this->admin,$bad),'lines.0.quantity'); }
        foreach (['-1','1e2','1.001',0.1,'10000000000000000'] as $price) { $bad=$data;$bad['lines'][0]['unit_price']=$price;$this->invalid(fn () => $service->draft($this->admin,$bad),'lines.0.unit_price'); }
        $this->invalid(fn () => $service->draft($this->admin,[...$data,'lines'=>[]]),'lines');
        $this->invalid(fn () => $service->draft($this->admin,[...$data,'lines'=>array_fill(0,51,$data['lines'][0])]),'lines');
        $this->invalid(fn () => $service->draft($this->admin,[...$data,'lines'=>[$data['lines'][0],$data['lines'][0]]]),'lines.0.product_id');
        $this->invalid(fn () => $service->draft($this->admin,[...$data,'supplier_id'=>'9223372036854775807']),'supplier_id');
        $this->supplier->update(['is_active'=>false]);$this->invalid(fn () => $service->draft($this->admin,$data),'supplier_id');
        $this->supplier->update(['is_active'=>true]);$this->first->update(['is_active'=>false]);
        $this->invalid(fn () => $service->draft($this->admin,$data),'lines.0.product_id');
    }

    public function test_total_limit_half_up_zero_and_exact_maximum(): void
    {
        $service=app(PurchaseService::class);
        $p=$service->draft($this->admin,$this->input([['product_id'=>$this->first->id,'quantity'=>'0.5','unit_price'=>'0.01'],['product_id'=>$this->second->id,'quantity'=>'1','unit_price'=>'0']]));
        $this->assertSame('0.01',$p->total);$service->confirm($this->admin,$p->id,1);
        $max=$service->draft($this->admin,$this->input([['product_id'=>$this->first->id,'quantity'=>'1','unit_price'=>'9999999999999999.99']]));
        $this->assertSame('9999999999999999.99',$service->confirm($this->admin,$max->id,1)->total);
        $state=$this->financialState();
        $bad=$this->input([['product_id'=>$this->first->id,'quantity'=>'1','unit_price'=>'9999999999999999.99'],['product_id'=>$this->second->id,'quantity'=>'1','unit_price'=>'0.01']]);
        $this->invalid(fn () => $service->draft($this->admin,$bad),'total');$this->assertSame($state,$this->financialState());
    }

    public function test_tampered_total_rolls_back_prior_line_and_snapshot_is_historical(): void
    {
        $service=app(PurchaseService::class);$p=$service->draft($this->admin,$this->input());
        DB::table('purchases')->where('id',$p->id)->update(['total'=>'0']);$state=$this->financialState();
        $this->invalid(fn () => $service->confirm($this->admin,$p->id,1),'total');$this->assertSame($state,$this->financialState());
        DB::table('purchases')->where('id',$p->id)->update(['total'=>'26.56']);
        $name=$this->supplier->name;$this->supplier->update(['name'=>'Đổi tên đối tác']);
        $p=$service->confirm($this->admin,$p->id,1);$this->assertSame($name,$p->supplier_name);
    }

    public function test_actor_role_and_inactive_references_are_checked_at_confirmation(): void
    {
        $service=app(PurchaseService::class);$this->forbidden(fn () => $service->draft($this->user,$this->input()));
        $p=$service->draft($this->admin,$this->input());$state=$this->financialState();
        $this->supplier->update(['is_active'=>false]);$this->invalid(fn () => $service->confirm($this->admin,$p->id,1),'supplier_id');
        $this->supplier->update(['is_active'=>true]);$this->first->update(['is_active'=>false]);$this->invalid(fn () => $service->confirm($this->admin,$p->id,1),'product_id');
        $this->first->update(['is_active'=>true]);
        DB::table('users')->where('id',$this->admin->id)->update(['is_active'=>0]);$this->forbidden(fn () => $service->confirm($this->admin,$p->id,1));
        DB::table('users')->where('id',$this->admin->id)->update(['is_active'=>1]);
        $this->user->role='admin';$this->forbidden(fn () => $service->confirm($this->user,$p->id,1));
        $this->assertSame($state,$this->financialState());
    }

    public function test_mysql_confirmed_immutability_check_and_foreign_keys(): void
    {
        $p=app(PurchaseService::class)->draft($this->admin,$this->input());
        foreach ([fn()=>DB::table('purchase_lines')->where('purchase_id',$p->id)->update(['quantity'=>'0']),fn()=>DB::table('purchases')->where('id',$p->id)->update(['supplier_id'=>'9223372036854775807'])] as $call) {
            try { $call();$this->fail('Expected constraint'); } catch (QueryException $e) { $this->assertContains($e->errorInfo[1],[3819,1452]); }
        }
        $p=app(PurchaseService::class)->confirm($this->admin,$p->id,1);$line=$p->lines->first();
        foreach ([fn()=>DB::table('purchases')->where('id',$p->id)->update(['note'=>'tamper']),fn()=>DB::table('purchases')->where('id',$p->id)->delete(),fn()=>DB::table('purchase_lines')->where('id',$line->id)->update(['quantity'=>'1']),fn()=>DB::table('purchase_lines')->where('id',$line->id)->delete(),fn()=>DB::table('purchase_lines')->insert([...$line->getAttributes(),'id'=>null])] as $call) {
            try { $call();$this->fail('Expected trigger'); } catch (QueryException $e) { $this->assertSame(1644,$e->errorInfo[1]); }
        }
        $this->invalid(fn () => $p->update(['note'=>'tamper']),'purchase');$this->invalid(fn () => $line->update(['unit_price'=>'0']),'purchase');
        try { $this->supplier->delete();$this->fail('Expected FK RESTRICT'); } catch (QueryException $e) { $this->assertSame(1451,$e->errorInfo[1]); }
        try { $this->first->delete();$this->fail('Expected FK RESTRICT'); } catch (QueryException $e) { $this->assertSame(1451,$e->errorInfo[1]); }
    }

    public function test_supplier_crud_validation_search_paging_xss_and_restrict(): void
    {
        $this->browse('GET','/suppliers')->assertRedirect('/login');$this->login($this->admin);
        $this->browse('GET','/suppliers/create')->assertOk()->assertSee('Thêm nhà cung cấp');
        $data=['code'=>'SUP-'.bin2hex(random_bytes(4)),'name'=>'<script>alert(1)</script>','is_active'=>'1','email'=>'bad'];
        $this->browse('POST','/suppliers',$data,true)->assertStatus(419);$data['_token']=$this->app['session']->token();
        $this->browse('POST','/suppliers',$data,true)->assertUnprocessable()->assertJsonValidationErrors('email');$data['email']='qa@example.test';
        $id=$this->browse('POST','/suppliers',$data,true)->assertCreated()->json('data.id');
        $this->browse('GET','/suppliers/'.$id)->assertOk()->assertSee('&lt;script&gt;',false)->assertDontSee('<script>alert(1)</script>',false);
        $this->browse('GET','/suppliers/'.$id.'/edit')->assertOk();
        $this->browse('POST','/suppliers',$data,true)->assertUnprocessable()->assertJsonValidationErrors('code');
        $this->browse('PATCH','/suppliers/'.$id,[...$data,'name'=>'Đã sửa','is_active'=>'0'],true)->assertOk()->assertJsonPath('data.is_active',false);
        $this->browse('GET','/suppliers?q='.$data['code'].'&is_active=0&per_page=1',[],true)->assertOk()->assertJsonPath('total',1);
        $this->browse('GET','/suppliers?per_page=51',[],true)->assertUnprocessable();
        $this->browse('DELETE','/suppliers/'.$id,['_token'=>$this->app['session']->token()],true)->assertNoContent();
        app(PurchaseService::class)->draft($this->admin,$this->input());
        $this->browse('DELETE','/suppliers/'.$this->supplier->id,['_token'=>$this->app['session']->token()],true)->assertUnprocessable()->assertJsonValidationErrors('supplier');
    }

    public function test_http_purchase_draft_edit_confirm_replay_filters_and_print(): void
    {
        $this->login($this->admin);$this->browse('GET','/purchases/create')->assertOk()->assertSee('purchases.js')->assertSee('Nhà cung cấp');
        $data=[...$this->input(),'_token'=>$this->app['session']->token()];
        $id=$this->browse('POST','/purchases',$data,true)->assertOk()->assertJsonPath('data.total','26.56')->json('data.id');
        $this->browse('GET','/purchases/'.$id)->assertOk()->assertSee('Sửa nháp');
        $this->browse('GET','/purchases/'.$id.'/edit')->assertOk();
        $this->browse('GET','/purchases/'.$id.'/print',[],true)->assertStatus(409);
        $this->browse('PATCH','/purchases/'.$id,[...$data,'version'=>1],true)->assertOk()->assertJsonPath('data.version',2);
        $this->browse('POST','/purchases/'.$id.'/confirm',['_token'=>$this->app['session']->token(),'version'=>1],true)->assertUnprocessable();
        $this->browse('POST','/purchases/'.$id.'/confirm',['_token'=>$this->app['session']->token(),'version'=>2],true)->assertOk()->assertJsonPath('data.status','confirmed');
        $state=$this->financialState();$this->browse('POST','/purchases/'.$id.'/confirm',['_token'=>$this->app['session']->token(),'version'=>2],true)->assertOk();$this->assertSame($state,$this->financialState());
        $this->browse('GET','/purchases/'.$id.'/print')->assertOk()->assertSee('Phiếu nhập nội bộ')->assertSee('26.56')->assertSee('sales-print.css');
        foreach (['?q=PN-'.str_pad($id,8,'0',STR_PAD_LEFT),'?q='.$this->first->code,'?supplier_id='.$this->supplier->id.'&status=confirmed&per_page=1','?q='.urlencode($this->supplier->name)] as $query) $this->browse('GET','/purchases'.$query,[],true)->assertOk()->assertJsonPath('total',1);
        $this->browse('GET','/purchases?from=2026-12-31&to=2026-01-01',[],true)->assertUnprocessable();
        $this->browse('GET','/purchases?per_page=51',[],true)->assertUnprocessable();
        $this->browse('GET','/purchases/9223372036854775807',[],true)->assertNotFound();
    }

    public function test_purchase_demo_seeder_is_test_only_and_replay_safe(): void
    {
        (new \Database\Seeders\PurchaseDemoSeeder)->run();
        $p=Purchase::where('client_key','c3000000-0000-4000-8000-000000000001')->firstOrFail();
        $this->assertSame('confirmed',$p->status);$this->assertSame('420000.00',$p->total);
        $this->assertCount(2,$p->lines);$state=$this->financialState();
        (new \Database\Seeders\PurchaseDemoSeeder)->run();$this->assertSame($state,$this->financialState());
    }

    public function test_unit_change_before_confirmation_requires_saving_draft_again(): void
    {
        $service=app(PurchaseService::class);$data=$this->input();$p=$service->draft($this->admin,$data);
        $unit=\App\Models\Unit::create(['code'=>'QA-C3-'.bin2hex(random_bytes(5)),'name'=>'Đơn vị khác','is_active'=>true]);
        $this->first->update(['unit_id'=>$unit->id]);$state=$this->financialState();
        $this->invalid(fn () => $service->confirm($this->admin,$p->id,1),'lines');$this->assertSame($state,$this->financialState());
        $p=$service->draft($this->admin,[...$data,'version'=>1],$p->id);$service->confirm($this->admin,$p->id,2);
        $this->assertSame($unit->name,$p->fresh()->lines->first()->unit_name);
    }

    public function test_user_can_read_but_all_supplier_purchase_writes_are_forbidden(): void
    {
        $p=app(PurchaseService::class)->draft($this->admin,$this->input());$this->login($this->user);
        foreach (['/suppliers','/suppliers/'.$this->supplier->id,'/purchases','/purchases/'.$p->id] as $url) $this->browse('GET',$url)->assertOk();
        $this->browse('GET','/purchases/'.$p->id)->assertDontSee('Xác nhận nhập hàng và cộng kho')->assertDontSee('Sửa nháp');
        foreach (['/suppliers/create','/suppliers/'.$this->supplier->id.'/edit','/purchases/create','/purchases/'.$p->id.'/edit'] as $url) $this->browse('GET',$url,[],true)->assertForbidden();
        foreach ([['POST','/suppliers'],['PATCH','/suppliers/'.$this->supplier->id],['DELETE','/suppliers/'.$this->supplier->id],['POST','/purchases'],['PATCH','/purchases/'.$p->id],['POST','/purchases/'.$p->id.'/confirm']] as [$method,$url]) $this->browse($method,$url,['_token'=>$this->app['session']->token()],true)->assertForbidden();
        $this->browse('GET','/inventory/in')->assertForbidden();
    }
}
