<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class G5PresentationTest extends TestCase
{
    private array $cookies = [];
    private array $sessions = [];
    private string $prefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertFalse($this->app->runningUnitTests());
        $this->assertSame(\App\Support\InventoryTestProfile::DATABASE, DB::selectOne('SELECT DATABASE() AS db')->db);
        DB::beginTransaction();
        $this->prefix = 'QA-G5-'.bin2hex(random_bytes(5));
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        Auth::forgetGuards();
        foreach (array_unique($this->sessions) as $id) {
            if (preg_match('/^[A-Za-z0-9]{40}$/D', $id)) {
                $path = config('session.files').'/'.$id;
                if (is_file($path)) {
                    unlink($path);
                }
            }
        }
        parent::tearDown();
    }

    private function browse(string $method, string $uri, array $data = [], bool $json = false)
    {
        Auth::forgetGuards();
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $this->app->forgetInstance('redirect');
        $response = $this->call($method, $uri, $data, $this->cookies, [], ['HTTP_ACCEPT' => $json ? 'application/json' : 'text/html', 'HTTP_REFERER' => url('/units/create'), 'REMOTE_ADDR' => '127.0.0.1']);
        foreach ($response->headers->getCookies() as $cookie) {
            $this->cookies[$cookie->getName()] = $cookie->getValue();
        }
        $this->sessions[] = $this->app['session']->driver()->getId();

        return $response;
    }

    private function token(): string
    {
        return $this->app['session']->driver()->token();
    }

    private function login(bool $admin = true): void
    {
        $this->browse('GET', '/login')->assertOk();
        $this->browse('POST', '/login', ['_token' => $this->token(), 'username' => $admin ? 'admin_demo' : 'nhanvien_demo_1', 'password' => getenv($admin ? 'MNM_DEMO_ADMIN_PASSWORD' : 'MNM_DEMO_USER_PASSWORD')])->assertRedirect();
    }

    public function test_dashboard_reads_real_counts_and_does_not_write_business_data(): void
    {
        $this->browse('GET', '/dashboard')->assertRedirect('/login');
        $this->browse('GET', '/dashboard', [], true)->assertUnauthorized();
        $this->login();
        $counts = ['products' => Product::count(), 'active_products' => Product::where('is_active', true)->count(), 'categories' => ProductCategory::count(), 'units' => Unit::count(), 'users' => User::count()];
        $writes = [];
        DB::listen(function ($query) use (&$writes) {
            if (preg_match('/^\s*(insert|update|delete|replace|alter|create|drop|truncate)\b/i', $query->sql)) {
                $writes[] = $query->sql;
            }
        });
        $this->browse('GET', '/dashboard')->assertOk()->assertViewHas('statistics', $counts)->assertSee('Phân bố danh mục')->assertSee('Sắp xếp theo tên')->assertSee($counts['users'].' tài khoản đang được quản lý.');
        $this->assertSame([], $writes);
    }

    public function test_user_dashboard_hides_accounts_and_server_blocks_admin_forms(): void
    {
        $this->login(false);
        $page = $this->browse('GET', '/dashboard')->assertOk()->assertDontSee('/users', false)->assertDontSee('Thêm vật tư');
        $this->assertArrayNotHasKey('users', $page->viewData('statistics'));
        $this->browse('GET', '/products')->assertOk()->assertDontSee('Thêm mới')->assertDontSee('data-confirm-delete', false);
        $this->browse('GET', '/products/create', [], true)->assertForbidden();
        $this->browse('GET', '/users', [], true)->assertForbidden();
        $this->browse('POST', '/logout', ['_token' => $this->token()])->assertRedirect('/login');
        $this->browse('GET', '/dashboard')->assertRedirect('/login');
    }

    public function test_all_resource_html_screens_preserve_forms_csrf_and_relationship_labels(): void
    {
        $this->login();
        foreach (['categories' => ProductCategory::class, 'units' => Unit::class, 'products' => Product::class, 'users' => User::class] as $path => $model) {
            $record = $model::firstOrFail();
            foreach (['', '/create', '/'.$record->id, '/'.$record->id.'/edit'] as $suffix) {
                $page = $this->browse('GET', '/'.$path.$suffix)->assertOk()->assertSee('/assets/bootstrap/bootstrap.min.css', false)->assertSee('/assets/bootstrap/bootstrap.bundle.min.js', false)->assertSee('/assets/app.css', false)->assertSee('Đến nội dung chính')->assertSee('name="_token"', false);
                $page->assertDontSee('cdn.jsdelivr.net', false)->assertDontSee('fonts.googleapis.com', false)->assertDontSee('password_hash', false);
                if (str_ends_with($suffix, '/edit')) {
                    $page->assertSee('name="_method" value="PUT"', false);
                }
                if ($suffix === '/create') {
                    $page->assertSee('type="submit"', false)->assertSee('Lưu');
                }
            }
        }
        $product = Product::with(['category', 'unit'])->firstOrFail();
        $this->browse('GET', '/products/'.$product->id)->assertOk()->assertSee($product->category->name)->assertSee($product->unit->name)->assertSee($product->sale_price);
        $admin = User::where('role', 'admin')->firstOrFail();
        $this->browse('GET', '/users/'.$admin->id)->assertOk()->assertDontSee('data-confirm-delete=', false)->assertDontSee($admin->password_hash, false);
    }

    public function test_filter_pagination_empty_state_and_escaped_delete_label(): void
    {
        $this->login();
        for ($index = 0; $index < 3; $index++) {
            Unit::create(['code' => $this->prefix.'-'.$index, 'name' => $this->prefix.' <script>bad()</script> '.$index]);
        }
        $query = '/units?'.http_build_query(['q' => $this->prefix, 'is_active' => 1, 'per_page' => 1]);
        $this->browse('GET', $query)->assertOk()->assertSee('Trang 1 / 3')->assertSee('page=2', false)->assertSee('per_page=1', false)->assertSee('is_active=1', false)->assertSee('data-confirm-delete=', false)->assertSee('&lt;script&gt;bad()&lt;/script&gt;', false)->assertDontSee('<script>bad()</script>', false)->assertSee('value="1" selected', false);
        $this->browse('GET', $query.'&page=2')->assertOk()->assertSee('Trang 2 / 3');
        $this->browse('GET', '/units?q='.$this->prefix.'-absent')->assertOk()->assertSee('Chưa có dữ liệu phù hợp.')->assertSee('Hiển thị 0–0 / 0 kết quả');
        $this->browse('GET', '/units?active_only=1')->assertOk()->assertSee('value="1" selected', false);
        $product = Product::firstOrFail();
        $this->browse('GET', '/products?'.http_build_query(['category_id' => $product->category_id, 'unit_id' => $product->unit_id]))->assertOk()->assertSee('filter-category', false)->assertSee('filter-unit', false)->assertSee($product->name);
    }

    public function test_html_validation_old_input_success_and_real_csrf_are_retained(): void
    {
        $this->login();
        $this->browse('POST', '/units', ['code' => $this->prefix, 'name' => 'No CSRF'], true)->assertStatus(419);
        $this->browse('GET', '/units/create')->assertOk();
        $this->browse('POST', '/units', ['_token' => $this->token(), 'code' => $this->prefix, 'name' => ''])->assertRedirect('/units/create')->assertSessionHasErrors('name')->assertSessionHasInput('code', $this->prefix);
        $this->browse('GET', '/units/create')->assertOk()->assertSee('role="alert"', false)->assertSee('aria-invalid="true"', false)->assertSee('id="name-error"', false)->assertSee($this->prefix)->assertSee('is-invalid', false);
        $this->browse('POST', '/units', ['_token' => $this->token(), 'code' => $this->prefix, 'name' => 'G5 kiểm biểu mẫu'])->assertRedirect('/units');
        $this->browse('GET', '/units')->assertOk()->assertSee('Đã tạo dữ liệu.')->assertSee('G5 kiểm biểu mẫu');
        $unit = Unit::where('code', $this->prefix)->firstOrFail();
        $this->browse('POST', '/units/'.$unit->id, ['_token' => $this->token(), '_method' => 'PUT', 'name' => 'G5 đã cập nhật'])->assertRedirect('/units');
        $this->assertSame('G5 đã cập nhật', $unit->fresh()->name);
        $this->browse('POST', '/units/'.$unit->id, ['_token' => $this->token(), '_method' => 'DELETE'])->assertRedirect('/units');
        $this->assertNull($unit->fresh());
    }
}
