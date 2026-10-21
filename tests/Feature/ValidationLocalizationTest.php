<?php

namespace Tests\Feature;

use App\Http\Requests\PurchaseWriteRequest;
use App\Http\Requests\SaleWriteRequest;
use App\Http\Requests\SupplierWriteRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ValidationLocalizationTest extends TestCase
{
    private array $cookies = [];
    private array $sessions = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('mnm_inventory_test_20261021', DB::selectOne('SELECT DATABASE() AS db')->db);
        DB::beginTransaction();
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

    private function browse(string $method, string $uri, array $data = [], bool $json = true, string $referer = '/sales/create')
    {
        Auth::forgetGuards();
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $this->app->forgetInstance('redirect');
        $response = $this->call($method, $uri, $data, $this->cookies, [], ['HTTP_ACCEPT' => $json ? 'application/json' : 'text/html', 'HTTP_REFERER' => url($referer), 'REMOTE_ADDR' => '127.0.0.1']);
        foreach ($response->headers->getCookies() as $cookie) $this->cookies[$cookie->getName()] = $cookie->getValue();
        $this->sessions[] = $this->app['session']->driver()->getId();
        return $response;
    }

    private function login(string $username): void
    {
        $actor = User::where('username', $username)->firstOrFail();
        $this->browse('GET', '/login', [], false)->assertOk();
        $this->browse('POST', '/login', ['_token' => $this->app['session']->token(), 'username' => $username, 'password' => getenv($actor->role === 'admin' ? 'MNM_DEMO_ADMIN_PASSWORD' : 'MNM_DEMO_USER_PASSWORD')], false)->assertRedirect();
    }

    private function duplicateInput(array $indices): array
    {
        $line = ['product_id' => '465', 'quantity' => '1', 'unit_price' => '12500'];
        return ['client_key' => (string) Str::uuid(), 'supplier_id' => '44', 'version' => '1', 'note' => 'Kiểm thông báo tiếng Việt', 'lines' => array_fill_keys($indices, $line)];
    }

    private function message(int $index): string
    {
        return 'Vật tư ở dòng '.($index + 1).' bị trùng. Mỗi vật tư chỉ được chọn một lần trong phiếu.';
    }

    public static function duplicateCases(): iterable
    {
        foreach (['sales' => '96', 'purchases' => '49'] as $module => $draft) {
            foreach (['POST' => '/'.$module, 'PATCH' => '/'.$module.'/'.$draft] as $method => $uri) {
                foreach ([[0, 1], [0, 2], [2, 5]] as $indices) yield $module.' '.$method.' '.implode('/', $indices) => [$module, $method, $uri, $indices];
            }
        }
    }

    #[DataProvider('duplicateCases')]
    public function test_duplicate_product_messages_are_vietnamese_for_create_edit_and_sparse_indices(string $module, string $method, string $uri, array $indices): void
    {
        $this->login($module === 'sales' ? 'nhanvien_demo_1' : 'admin_demo');
        $response = $this->browse($method, $uri, [...$this->duplicateInput($indices), '_token' => $this->app['session']->token()]);
        $response->assertUnprocessable();
        $expected = [];
        foreach ($indices as $index) $expected['lines.'.$index.'.product_id'] = [$this->message($index)];
        $this->assertSame($expected, $response->json('errors'));
    }

    public static function modules(): iterable
    {
        yield 'sales' => ['sales', SaleWriteRequest::class, 'nhanvien_demo_1'];
        yield 'purchases' => ['purchases', PurchaseWriteRequest::class, 'admin_demo'];
    }

    #[DataProvider('modules')]
    public function test_html_redirect_keeps_sparse_old_input_and_displays_translated_errors(string $module, string $requestClass, string $username): void
    {
        $this->login($username);
        $referer = '/'.$module.'/create';
        $response = $this->browse('POST', '/'.$module, [...$this->duplicateInput([0, 2]), '_token' => $this->app['session']->token()], false, $referer);
        $response->assertRedirect(url($referer));
        $this->assertSame([0, 2], array_keys($this->app['session']->getOldInput('lines')));
        $html = $this->browse('GET', $referer, [], false);
        $html->assertOk()->assertSee($this->message(0))->assertSee($this->message(2))->assertSee('name="lines[2][product_id]"', false)->assertDontSee('The lines.');
    }

    #[DataProvider('modules')]
    public function test_unchanged_rules_accept_unique_sparse_lines_and_reject_missing_products(string $module, string $requestClass, string $username): void
    {
        $input = $this->duplicateInput([0, 2]);
        $input['lines'][2]['product_id'] = '466';
        $request = $requestClass::create('/'.$module, 'POST', $input);
        $this->assertContains('distinct:strict', $request->rules()['lines.*.product_id']);
        $this->assertTrue(Validator::make($input, $request->rules())->passes());
        unset($input['lines'][2]['product_id']);
        $this->assertArrayHasKey('lines.2.product_id', Validator::make($input, $request->rules())->errors()->toArray());
    }

    public function test_supplier_address_limit_is_unchanged_and_create_edit_messages_are_translated(): void
    {
        $this->login('admin_demo');
        $input = ['code' => 'QA-L10N-'.bin2hex(random_bytes(4)), 'name' => 'Nhà cung cấp kiểm thông báo', 'address' => str_repeat('Đ', 501), 'is_active' => '1'];
        foreach (['POST' => '/suppliers', 'PATCH' => '/suppliers/45'] as $method => $uri) {
            $this->browse($method, $uri, [...$input, '_token' => $this->app['session']->token()])->assertUnprocessable()->assertJsonPath('errors.address.0', 'Địa chỉ không được dài quá 500 ký tự.');
        }
        $request = SupplierWriteRequest::create('/suppliers', 'POST', $input);
        $this->assertContains('max:500', $request->rules()['address']);
        $input['address'] = str_repeat('Đ', 500);
        $this->assertTrue(Validator::make($input, $request->rules())->passes());
    }

    public function test_supplier_html_redirect_keeps_address_and_vietnamese_label(): void
    {
        $this->login('admin_demo');
        $input = ['code' => 'QA-L10N-'.bin2hex(random_bytes(4)), 'name' => 'Nhà cung cấp kiểm thông báo', 'address' => str_repeat('Đ', 501), 'is_active' => '1', '_token' => $this->app['session']->token()];
        $this->browse('POST', '/suppliers', $input, false, '/suppliers/create')->assertRedirect(url('/suppliers/create'));
        $this->assertSame($input['address'], $this->app['session']->getOldInput('address'));
        $this->browse('GET', '/suppliers/create', [], false)->assertOk()->assertSee('Địa chỉ không được dài quá 500 ký tự.')->assertDontSee('Address không được');
    }

    public function test_user_write_denials_and_csrf_are_unchanged(): void
    {
        $this->login('nhanvien_demo_1');
        foreach (['POST' => ['/suppliers', '/purchases'], 'PATCH' => ['/suppliers/45', '/purchases/49']] as $method => $uris) {
            foreach ($uris as $uri) $this->browse($method, $uri, ['_token' => $this->app['session']->token()])->assertForbidden();
        }
        $this->browse('POST', '/sales', $this->duplicateInput([0, 2]))->assertStatus(419);
    }
}
