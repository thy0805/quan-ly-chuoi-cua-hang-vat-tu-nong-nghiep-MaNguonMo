<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class G4BHttpTest extends TestCase
{
    private const PASSWORD = 'G4B-test-fixture-only!';

    private array $cookies = [];
    private array $sessionIds = [];
    private string $prefix;
    private User $employee;
    private User $inactive;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertFalse($this->app->runningUnitTests());
        $this->assertSame('mnm_vat_tu_nong_nghiep', DB::selectOne('SELECT DATABASE() AS db')->db);
        DB::beginTransaction();
        $this->prefix = 'QA-G4B-'.bin2hex(random_bytes(5));
        $hash = Hash::make(self::PASSWORD);
        $this->employee = User::create(['username' => $this->prefix.'-user', 'password_hash' => $hash]);
        $this->inactive = User::create(['username' => $this->prefix.'-inactive', 'password_hash' => $hash, 'is_active' => false]);
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        Auth::forgetGuards();
        foreach (array_unique($this->sessionIds) as $id) {
            if (preg_match('/^[A-Za-z0-9]{40}$/D', $id)) {
                $path = storage_path('mnm/framework/sessions/'.$id);
                if (is_file($path)) {
                    unlink($path);
                }
            }
        }
        parent::tearDown();
    }

    private function browse(string $method, string $uri, array $data = [], bool $json = true)
    {
        Auth::forgetGuards();
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $response = $this->call($method, $uri, $data, $this->cookies, [], ['HTTP_ACCEPT' => $json ? 'application/json' : 'text/html', 'REMOTE_ADDR' => '127.0.0.1']);
        foreach ($response->headers->getCookies() as $cookie) {
            $this->cookies[$cookie->getName()] = $cookie->getValue();
        }
        $this->sessionIds[] = $this->app['session']->driver()->getId();

        return $response;
    }

    private function token(): string
    {
        return $this->app['session']->driver()->token();
    }

    private function login(bool $admin = true): void
    {
        $this->browse('GET', '/login', [], false)->assertOk()->assertSee('name="_token"', false);
        $this->browse('POST', '/login', ['_token' => $this->token(), 'username' => $admin ? 'admin_demo' : $this->employee->username, 'password' => $admin ? getenv('MNM_DEMO_ADMIN_PASSWORD') : self::PASSWORD])->assertOk();
    }

    private function write(string $method, string $uri, array $data = [])
    {
        return $this->browse($method, $uri, ['_token' => $this->token(), ...$data]);
    }

    private function fixtures(): array
    {
        $category = ProductCategory::create(['code' => $this->prefix.'-CAT', 'name' => $this->prefix.' Danh mục 🌱']);
        $unit = Unit::create(['code' => $this->prefix.'-UNIT', 'name' => $this->prefix.' Đơn vị']);
        $product = Product::create(['code' => $this->prefix.'-PRODUCT', 'name' => $this->prefix.' Phân hữu cơ 🌱', 'category_id' => $category->id, 'unit_id' => $unit->id, 'sale_price' => '12.34']);

        return [$category, $unit, $product];
    }

    public function test_guest_redirect_json_unauthorized_and_real_csrf_rejection(): void
    {
        $this->browse('GET', '/products', [], false)->assertRedirect('/login');
        $this->browse('GET', '/products')->assertUnauthorized();
        $this->browse('GET', '/login', [], false)->assertOk()->assertSee('Đăng nhập')->assertSee('name="_token"', false);
        $this->browse('POST', '/login', ['username' => $this->employee->username, 'password' => self::PASSWORD])->assertStatus(419);
        $this->write('POST', '/login', ['username' => []])->assertUnprocessable()->assertJsonValidationErrors(['username', 'password']);
    }

    public function test_wrong_password_and_inactive_login_are_rejected_without_hash_leak(): void
    {
        $this->browse('GET', '/login');
        foreach ([[$this->employee->username, 'wrong'], [$this->inactive->username, self::PASSWORD]] as [$username, $password]) {
            $this->write('POST', '/login', compact('username', 'password'))->assertUnprocessable()->assertJsonValidationErrors('username')->assertDontSee($this->employee->password_hash, false);
            $this->browse('GET', '/products')->assertUnauthorized();
        }
    }

    public function test_session_fixation_logout_and_old_cookie_replay(): void
    {
        $this->browse('GET', '/login');
        $anonymousCookies = $this->cookies;
        $anonymousId = $this->app['session']->driver()->getId();
        $anonymousToken = $this->token();
        $this->write('POST', '/login', ['username' => $this->employee->username, 'password' => self::PASSWORD])->assertOk()->assertJsonMissingPath('user.password_hash');
        $authenticatedId = $this->app['session']->driver()->getId();
        $authenticatedToken = $this->token();
        $authenticatedCookies = $this->cookies;
        $this->assertNotSame($anonymousId, $authenticatedId);
        $this->assertNotSame($anonymousToken, $authenticatedToken);
        $this->cookies = $anonymousCookies;
        $this->browse('GET', '/products')->assertUnauthorized();
        $this->cookies = $authenticatedCookies;
        $this->browse('GET', '/products')->assertOk();
        $this->write('POST', '/logout')->assertNoContent();
        $this->assertNotSame($authenticatedId, $this->app['session']->driver()->getId());
        $this->assertNotSame($authenticatedToken, $this->token());
        $this->cookies = $authenticatedCookies;
        $this->browse('GET', '/products')->assertUnauthorized();
    }

    public function test_login_throttling_limits_repeated_failures(): void
    {
        $key = 'mnm-login:'.hash('sha256', mb_strtolower($this->employee->username).'|127.0.0.1');
        try {
            $this->browse('GET', '/login');
            for ($attempt = 0; $attempt < 5; $attempt++) {
                $this->write('POST', '/login', ['username' => $this->employee->username, 'password' => 'wrong'])->assertUnprocessable();
            }
            $this->write('POST', '/login', ['username' => $this->employee->username, 'password' => self::PASSWORD])->assertStatus(429);
            $this->browse('GET', '/products')->assertUnauthorized();
        } finally {
            RateLimiter::clear($key);
        }
    }

    public function test_active_middleware_revokes_existing_session(): void
    {
        $this->login(false);
        $this->employee->update(['is_active' => false]);
        $this->browse('GET', '/products')->assertForbidden();
        $this->browse('GET', '/products')->assertUnauthorized();
    }

    public function test_user_read_access_and_server_side_write_denial(): void
    {
        [$category, $unit, $product] = $this->fixtures();
        $this->login(false);
        foreach (['categories' => $category, 'units' => $unit, 'products' => $product] as $catalog => $record) {
            $this->browse('GET', '/'.$catalog)->assertOk();
            $this->browse('GET', '/'.$catalog.'/'.$record->id)->assertOk();
            $this->browse('GET', '/'.$catalog.'/create')->assertForbidden();
            $this->browse('GET', '/'.$catalog.'/'.$record->id.'/edit')->assertForbidden();
            $this->write('POST', '/'.$catalog, ['code' => 'ATTACK', 'name' => 'Không được ghi'])->assertForbidden();
            $this->write('PATCH', '/'.$catalog.'/'.$record->id, ['name' => 'Không được sửa'])->assertForbidden();
            $this->write('DELETE', '/'.$catalog.'/'.$record->id)->assertForbidden();
        }
        $this->browse('GET', '/users')->assertForbidden();
        $this->write('PATCH', '/users/'.$this->employee->id, ['role' => 'admin'])->assertForbidden();
        $this->browse('GET', '/products', [], false)->assertOk()->assertDontSee('Thêm mới')->assertDontSee('>Xóa<', false);
        $this->assertSame('user', $this->employee->fresh()->role);
        $this->assertStringContainsString('Phân hữu cơ', $product->fresh()->name);
    }

    public function test_admin_catalog_crud_and_restrict_over_http(): void
    {
        $this->login();
        foreach (['categories', 'units', 'products'] as $catalog) {
            $this->browse('GET', '/'.$catalog.'/create', [], false)->assertOk();
        }
        $category = $this->write('POST', '/categories', ['code' => $this->prefix.'-C', 'name' => 'Danh mục HTTP'])->assertCreated()->json('data');
        $unit = $this->write('POST', '/units', ['code' => $this->prefix.'-U', 'name' => 'Đơn vị HTTP'])->assertCreated()->json('data');
        $product = $this->write('POST', '/products', ['code' => $this->prefix.'-P', 'name' => 'Phân hữu cơ HTTP 🌱', 'category_id' => $category['id'], 'unit_id' => $unit['id'], 'sale_price' => '9999999999999999.99', 'tax_rate' => '100.00', 'expiry_warning_days' => 3650])->assertCreated()->assertJsonPath('data.sale_price', '9999999999999999.99')->json('data');
        $this->assertIsString($product['id']);
        $this->browse('GET', '/products/'.$product['id'].'/edit', [], false)->assertOk()->assertSee('Phân hữu cơ HTTP 🌱');
        $this->write('PATCH', '/products/'.$product['id'], ['sale_price' => '0', 'tax_rate' => null, 'expiry_warning_days' => null])->assertOk()->assertJsonPath('data.sale_price', '0.00')->assertJsonPath('data.tax_rate', null);
        $this->write('DELETE', '/categories/'.$category['id'])->assertUnprocessable()->assertJsonValidationErrors('delete');
        $this->write('DELETE', '/units/'.$unit['id'])->assertUnprocessable()->assertJsonValidationErrors('delete');
        $this->write('DELETE', '/products/'.$product['id'])->assertNoContent();
        $this->write('DELETE', '/units/'.$unit['id'])->assertNoContent();
        $this->write('DELETE', '/categories/'.$category['id'])->assertNoContent();
        $this->browse('GET', '/products/'.$product['id'])->assertNotFound();
    }

    public function test_category_cycle_active_parent_and_no_deactivation_cascade(): void
    {
        [$root] = $this->fixtures();
        $child = ProductCategory::create(['code' => $this->prefix.'-CHILD', 'name' => 'Nhóm con', 'parent_id' => $root->id]);
        $grandchild = ProductCategory::create(['code' => $this->prefix.'-GRAND', 'name' => 'Nhóm cháu', 'parent_id' => $child->id]);
        $this->login();
        $this->write('PATCH', '/categories/'.$root->id, ['parent_id' => $grandchild->id])->assertUnprocessable()->assertJsonValidationErrors('parent_id');
        $this->write('PATCH', '/categories/'.$root->id, ['parent_id' => $root->id])->assertUnprocessable()->assertJsonValidationErrors('parent_id');
        $this->write('DELETE', '/categories/'.$root->id)->assertUnprocessable();
        $this->write('PATCH', '/categories/'.$root->id, ['is_active' => false])->assertOk();
        $this->assertTrue($child->fresh()->is_active);
        $this->write('POST', '/categories', ['code' => $this->prefix.'-BAD', 'name' => 'Sai cha', 'parent_id' => $root->id, 'is_active' => false])->assertUnprocessable()->assertJsonValidationErrors('parent_id');
        $this->write('PATCH', '/categories/'.$child->id, ['is_active' => false])->assertOk();
        $this->write('PATCH', '/categories/'.$child->id, ['name' => 'Đổi tên nhóm ngừng'])->assertOk();
        $this->write('PATCH', '/categories/'.$child->id, ['is_active' => true])->assertUnprocessable();
    }

    public function test_product_validation_decimal_ids_and_inactive_reference_parity(): void
    {
        [$category, $unit, $product] = $this->fixtures();
        $this->login();
        foreach (['sale_price' => '-1', 'tax_rate' => '100.01', 'expiry_warning_days' => 3651] as $key => $value) {
            $this->write('PATCH', '/products/'.$product->id, [$key => $value])->assertUnprocessable()->assertJsonValidationErrors($key);
        }
        $this->write('PATCH', '/products/'.$product->id, ['sale_price' => '1.239'])->assertUnprocessable()->assertJsonValidationErrors('sale_price');
        $this->write('PATCH', '/products/'.$product->id, ['sale_price' => '10000000000000000.00'])->assertUnprocessable()->assertJsonValidationErrors('sale_price');
        $this->write('PATCH', '/products/'.$product->id, ['unit_id' => '9223372036854775808'])->assertUnprocessable();
        $this->write('PATCH', '/products/'.$product->id, ['category_id' => '9223372036854775807'])->assertUnprocessable();
        $this->write('PATCH', '/products/'.$product->id, ['is_active' => false])->assertOk();
        $this->write('PATCH', '/categories/'.$category->id, ['is_active' => false])->assertOk();
        $this->write('PATCH', '/units/'.$unit->id, ['is_active' => false])->assertOk();
        $this->write('PATCH', '/products/'.$product->id, ['name' => 'Đổi tên vật tư ngừng'])->assertOk();
        $this->write('PATCH', '/products/'.$product->id, ['category_id' => $category->id])->assertUnprocessable();
        $this->write('PATCH', '/products/'.$product->id, ['unit_id' => $unit->id])->assertUnprocessable();
        $this->write('PATCH', '/products/'.$product->id, ['is_active' => true])->assertUnprocessable();
    }

    public function test_search_unicode_wildcards_and_stable_pagination(): void
    {
        $ids = [];
        for ($index = 0; $index < 12; $index++) {
            $ids[] = Unit::create(['code' => $this->prefix.'-'.str_pad((string) $index, 2, '0', STR_PAD_LEFT), 'name' => $this->prefix.' Đồng tên 🌱'])->id;
        }
        $this->login(false);
        $query = '/units?'.http_build_query(['q' => $this->prefix.' đồng tên 🌱', 'per_page' => 10]);
        $first = $this->browse('GET', $query)->assertOk()->assertJsonPath('total', 12)->assertJsonPath('per_page', 10)->json('data');
        $second = $this->browse('GET', $query.'&page=2')->assertOk()->json('data');
        $this->assertSame($ids, array_column([...$first, ...$second], 'id'));
        $this->browse('GET', $query.'&per_page=1')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('per_page', 1);
        $this->browse('GET', $query.'&per_page=11')->assertOk()->assertJsonCount(11, 'data');
        Unit::create(['code' => $this->prefix.'-OFF', 'name' => $this->prefix.' Ngừng', 'is_active' => false]);
        $this->browse('GET', '/units?'.http_build_query(['q' => $this->prefix, 'active_only' => 1]))->assertOk()->assertJsonPath('total', 12);
        $this->browse('GET', '/units?'.http_build_query(['q' => $this->prefix, 'is_active' => 0]))->assertOk()->assertJsonPath('total', 1);
        $this->browse('GET', '/units?'.http_build_query(['q' => $this->prefix.'-0_']))->assertOk()->assertJsonPath('total', 10);
        $this->browse('GET', $query.'&page=3')->assertOk()->assertJsonCount(0, 'data');
        $html = $this->browse('GET', $query, [], false)->assertOk();
        $html->assertSee('Trang sau')->assertSee('per_page=10', false);
    }

    public function test_query_validation_and_not_found_contract(): void
    {
        $this->login(false);
        foreach (['page=0', 'page=-1', 'page=1e2', 'per_page=1000', 'q[]=bad', 'category_id=01', 'unit_id=9223372036854775808', 'is_active=bad', 'active_only=bad'] as $query) {
            $this->browse('GET', '/products?'.$query)->assertUnprocessable();
        }
        foreach (['0', '01', 'abc', '9223372036854775808', '9223372036854775807'] as $id) {
            $this->browse('GET', '/products/'.$id)->assertNotFound();
        }
        $this->browse('GET', '/products?'.http_build_query(['q' => "' OR 1=1 --"]))->assertOk()->assertJsonPath('total', 0);
    }

    public function test_username_password_user_crud_and_single_admin_guards(): void
    {
        $this->login();
        $this->browse('GET', '/users/create', [], false)->assertOk();
        $username = $this->prefix.'-managed';
        $payload = ['username' => $username, 'password' => self::PASSWORD, 'password_confirmation' => self::PASSWORD];
        $user = $this->write('POST', '/users', $payload)->assertCreated()->assertJsonMissingPath('data.password_hash')->assertJsonPath('data.role', 'user')->json('data');
        $this->assertTrue(Hash::check(self::PASSWORD, User::findOrFail($user['id'])->password_hash));
        $this->write('POST', '/users', $payload)->assertUnprocessable()->assertJsonValidationErrors('username');
        $this->write('POST', '/users', [...$payload, 'username' => $this->prefix.'-short', 'password' => 'short', 'password_confirmation' => 'short'])->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->write('POST', '/users', [...$payload, 'username' => $this->prefix.'-admin', 'role' => 'admin'])->assertUnprocessable()->assertJsonValidationErrors('role');
        $this->write('PATCH', '/users/'.$user['id'], ['password_hash' => 'raw'])->assertUnprocessable()->assertJsonValidationErrors('password_hash');
        $this->write('PATCH', '/users/'.$user['id'], ['role' => 'admin'])->assertUnprocessable();
        $unicodePassword = str_repeat('🌱', 19);
        $this->write('PATCH', '/users/'.$user['id'], ['password' => $unicodePassword, 'password_confirmation' => $unicodePassword])->assertUnprocessable()->assertJsonValidationErrors('password');
        $replacement = 'TenChars!1';
        $this->write('PATCH', '/users/'.$user['id'], ['password' => $replacement, 'password_confirmation' => $replacement])->assertOk()->assertJsonMissingPath('data.password_hash');
        $this->assertTrue(Hash::check($replacement, User::findOrFail($user['id'])->password_hash));
        $this->write('PATCH', '/users/'.$user['id'], ['username' => $username.'-edited', 'is_active' => false])->assertOk()->assertJsonPath('data.is_active', false);
        $this->browse('GET', '/users/'.$user['id'].'/edit', [], false)->assertOk()->assertDontSee(User::findOrFail($user['id'])->password_hash, false);
        $this->browse('GET', '/users?'.http_build_query(['q' => $username]))->assertOk()->assertJsonPath('total', 1);
        $this->write('DELETE', '/users/'.$user['id'])->assertNoContent();
        $admin = User::where('role', 'admin')->firstOrFail();
        $this->write('PATCH', '/users/'.$admin->id, ['is_active' => false])->assertUnprocessable()->assertJsonValidationErrors('role');
        $this->write('PATCH', '/users/'.$admin->id, ['role' => 'user'])->assertUnprocessable();
        $this->write('DELETE', '/users/'.$admin->id)->assertUnprocessable()->assertJsonValidationErrors('role');
        $this->assertTrue($admin->fresh()->is_active);
        $this->assertSame(1, User::where('role', 'admin')->count());
        $this->write('PATCH', '/users/9223372036854775808', ['username' => 'invalid-id'])->assertNotFound();
    }

    public function test_csrf_blocks_authenticated_writes_and_logout(): void
    {
        $this->login();
        $this->browse('POST', '/units', ['code' => $this->prefix, 'name' => 'Thiếu CSRF'])->assertStatus(419);
        $this->browse('POST', '/units', ['_token' => 'wrong-token', 'code' => $this->prefix, 'name' => 'Sai CSRF'])->assertStatus(419);
        $this->browse('POST', '/logout')->assertStatus(419);
        $this->browse('GET', '/products')->assertOk();
        $this->assertFalse(Unit::where('code', $this->prefix)->exists());
    }

    public function test_html_validation_redirect_and_output_escaping(): void
    {
        $this->login();
        $this->browse('POST', '/units', ['_token' => $this->token(), 'code' => $this->prefix, 'name' => ''], false)->assertRedirect();
        $unit = Unit::create(['code' => $this->prefix, 'name' => '<script>alert(1)</script>']);
        $this->browse('GET', '/units/'.$unit->id, [], false)->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->write('POST', '/units', ['code' => $this->prefix, 'name' => 'Trùng'])->assertUnprocessable()->assertJsonValidationErrors('code');
    }

    public function test_fk_rejects_reference_added_after_delete_precheck(): void
    {
        $category = ProductCategory::create(['code' => $this->prefix, 'name' => 'Danh mục race fixture']);
        $this->login();
        $originalDispatcher = ProductCategory::getEventDispatcher();
        ProductCategory::setEventDispatcher(clone $originalDispatcher);
        $insertedBetweenCheckAndDelete = false;
        ProductCategory::deleting(function ($record) use ($category, &$insertedBetweenCheckAndDelete) {
            if ($record->id === $category->id) {
                ProductCategory::create(['code' => $this->prefix.'-LATE', 'name' => 'Tham chiếu sau precheck', 'parent_id' => $category->id]);
                $insertedBetweenCheckAndDelete = true;
            }
        });
        try {
            $this->write('DELETE', '/categories/'.$category->id)->assertUnprocessable()->assertJsonValidationErrors('delete');
            $this->assertTrue($insertedBetweenCheckAndDelete);
            $this->assertNotNull($category->fresh());
            $this->assertFalse(ProductCategory::where('code', $this->prefix.'-LATE')->exists());
        } finally {
            ProductCategory::setEventDispatcher($originalDispatcher);
        }
    }
}
