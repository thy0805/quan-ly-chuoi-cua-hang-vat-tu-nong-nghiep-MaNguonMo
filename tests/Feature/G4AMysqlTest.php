<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use App\Support\DecimalId;
use App\Support\RuntimeIsolation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class G4AMysqlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        parent::tearDown();
    }

    private function databaseRejects(callable $operation, int $code): void
    {
        try {
            $operation();
            $this->fail('Expected MySQL constraint rejection.');
        } catch (QueryException $error) {
            $this->assertSame($code, $error->errorInfo[1]);
        }
    }

    private function validationRejects(callable $operation, string $field): void
    {
        try {
            $operation();
            $this->fail('Expected domain validation rejection.');
        } catch (ValidationException $error) {
            $this->assertArrayHasKey($field, $error->errors());
        }
    }

    public function test_schema_matches_signed_keys_relations_checks_and_generated_admin(): void
    {
        $identity = DB::selectOne('SELECT DATABASE() AS db, VERSION() AS version, CURRENT_USER() AS account');
        $this->assertSame(\App\Support\InventoryTestProfile::DATABASE, $identity->db);
        $this->assertStringStartsWith('8.4.', $identity->version);
        $this->assertSame('mnm_c1_test_runtime@localhost', $identity->account);
        $tables = DB::select("SELECT TABLE_NAME, ENGINE, TABLE_COLLATION FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ('migrations','product_categories','products','units','users') ORDER BY TABLE_NAME");
        $this->assertSame(['migrations', 'product_categories', 'products', 'units', 'users'], array_column($tables, 'TABLE_NAME'));
        foreach ($tables as $table) {
            $this->assertSame('InnoDB', $table->ENGINE);
            $this->assertSame('utf8mb4_0900_as_cs', $table->TABLE_COLLATION);
        }
        $foreign = DB::select("SELECT DELETE_RULE, UPDATE_RULE FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME IN ('product_categories','products','units','users')");
        $this->assertCount(3, $foreign);
        foreach ($foreign as $relation) {
            $this->assertSame('RESTRICT', $relation->DELETE_RULE);
            $this->assertSame('RESTRICT', $relation->UPDATE_RULE);
        }
        $checks = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_TYPE = 'CHECK' AND TABLE_NAME IN ('product_categories','products','units','users')");
        $this->assertCount(7, $checks);
        $keys = DB::select("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ('product_categories','products','units','users') AND COLUMN_NAME IN ('id', 'parent_id', 'category_id', 'unit_id')");
        $this->assertCount(7, $keys);
        foreach ($keys as $key) {
            $this->assertSame('bigint', $key->COLUMN_TYPE);
        }
        $admin = DB::selectOne("SELECT EXTRA,GENERATION_EXPRESSION FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='admin_slot'");
        $this->assertSame('STORED GENERATED', $admin->EXTRA);
        $this->assertStringContainsString('admin', $admin->GENERATION_EXPRESSION);
        $decimal = DB::selectOne("SELECT NUMERIC_PRECISION,NUMERIC_SCALE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='products' AND COLUMN_NAME='sale_price'");
        $this->assertSame(18, $decimal->NUMERIC_PRECISION);
        $this->assertSame(2, $decimal->NUMERIC_SCALE);
    }

    public function test_synthetic_seed_models_relations_casts_and_password_hashes(): void
    {
        $this->assertSame([3, 5, 3, 4], [User::whereIn('username', ['admin_demo', 'nhanvien_demo_1', 'nhanvien_demo_2'])->count(), ProductCategory::whereIn('code', ['PHANBON', 'HUUCO', 'VOSINH', 'BVTV', 'NGUNG'])->count(), Unit::whereIn('code', ['KG', 'CHAI', 'GOI'])->count(), Product::whereIn('code', ['DEMO-01', 'DEMO-02', 'DEMO-03', 'DEMO-04'])->count()]);
        $product = Product::where('code', 'DEMO-01')->firstOrFail();
        $this->assertSame('Phân bón hữu cơ 🌱', $product->name);
        $this->assertSame('HUUCO', $product->category->code);
        $this->assertSame('PHANBON', $product->category->parent->code);
        $this->assertCount(2, $product->category->parent->children);
        $this->assertTrue($product->category->products->contains($product));
        $this->assertTrue($product->unit->products->contains($product));
        $this->assertSame('KG', $product->unit->code);
        $this->assertIsString($product->getKey());
        $this->assertIsBool($product->is_active);
        $this->assertSame('0.00', $product->sale_price);
        $this->assertNull($product->tax_rate);
        $maximum = Product::where('code', 'DEMO-03')->firstOrFail();
        $this->assertSame('9999999999999999.99', $maximum->sale_price);
        $this->assertSame('100.00', $maximum->tax_rate);
        $this->assertSame(3650, $maximum->expiry_warning_days);
        $admin = User::where('role', 'admin')->firstOrFail();
        $this->assertSame('password_hash', $admin->getAuthPasswordName());
        $this->assertArrayNotHasKey('password_hash', $admin->toArray());
        $this->assertFalse(hash_equals(getenv('MNM_DEMO_ADMIN_PASSWORD'), $admin->password_hash));
        $this->assertTrue(Hash::check(getenv('MNM_DEMO_ADMIN_PASSWORD'), $admin->password_hash));
        $this->assertSame('UTC', $admin->created_at->timezoneName);
    }

    public function test_mysql_enforces_unique_single_admin_even_if_inactive_and_foreign_keys(): void
    {
        $product = Product::firstOrFail();
        $this->databaseRejects(fn () => DB::table('units')->insert(['code' => 'KG', 'name' => 'Trùng mã']), 1062);
        $this->databaseRejects(fn () => DB::table('product_categories')->insert(['code' => 'HUUCO', 'name' => 'Trùng mã']), 1062);
        $this->databaseRejects(fn () => DB::table('products')->where('code', 'DEMO-02')->update(['code' => 'DEMO-01']), 1062);
        $this->databaseRejects(fn () => DB::table('users')->insert(['username' => 'admin_demo', 'password_hash' => 'fixture']), 1062);
        $this->databaseRejects(fn () => DB::table('users')->insert(['username' => 'admin_second', 'password_hash' => 'fixture', 'role' => 'admin']), 1062);
        DB::table('users')->where('role', 'admin')->update(['is_active' => false]);
        $this->databaseRejects(fn () => DB::table('users')->insert(['username' => 'admin_second', 'password_hash' => 'fixture', 'role' => 'admin']), 1062);
        $this->databaseRejects(fn () => DB::table('products')->where('id', $product->id)->update(['category_id' => 9223372036854775807]), 1452);
        $this->databaseRejects(fn () => DB::table('products')->where('id', $product->id)->update(['unit_id' => 9223372036854775807]), 1452);
        $this->databaseRejects(fn () => DB::table('product_categories')->insert(['code' => 'BAD-FK', 'name' => 'Sai cha', 'parent_id' => 9223372036854775807]), 1452);
        $this->databaseRejects(fn () => DB::table('units')->where('id', $product->unit_id)->delete(), 1451);
        $this->databaseRejects(fn () => DB::table('product_categories')->where('id', $product->category_id)->delete(), 1451);
        $this->databaseRejects(fn () => DB::table('product_categories')->where('code', 'PHANBON')->delete(), 1451);
        $this->databaseRejects(fn () => DB::table('units')->where('id', $product->unit_id)->update(['id' => 9223372036854775806]), 1451);
    }

    public function test_mysql_checks_price_tax_expiry_booleans_and_triggers(): void
    {
        $id = Product::firstOrFail()->id;
        foreach (['sale_price' => '-0.01', 'tax_rate' => '100.01', 'expiry_warning_days' => 3651, 'is_active' => 2] as $column => $value) {
            $this->databaseRejects(fn () => DB::table('products')->where('id', $id)->update([$column => $value]), 3819);
        }
        $this->databaseRejects(fn () => DB::table('products')->where('id', $id)->update(['sale_price' => '10000000000000000.00']), 1264);
        foreach (['users', 'units', 'product_categories'] as $table) {
            $this->databaseRejects(fn () => DB::table($table)->where('id', 1)->update(['is_active' => 2]), 3819);
        }
        $category = ProductCategory::where('code', 'PHANBON')->firstOrFail();
        $this->databaseRejects(fn () => DB::table('product_categories')->where('id', $category->id)->update(['parent_id' => $category->id]), 1644);
        $this->databaseRejects(fn () => DB::table('product_categories')->insert(['id' => '900000001', 'parent_id' => '900000001', 'code' => 'SELF-INSERT', 'name' => 'Tự trỏ']), 1644);
        $this->assertNull($category->fresh()->parent_id);
    }

    public function test_domain_tree_cycle_active_parent_and_no_cascade_deactivation(): void
    {
        $root = ProductCategory::where('code', 'PHANBON')->firstOrFail();
        $child = ProductCategory::where('code', 'HUUCO')->firstOrFail();
        $grandchild = ProductCategory::create(['code' => 'GRANDCHILD', 'name' => 'Nhóm cháu', 'parent_id' => $child->id]);
        $this->validationRejects(fn () => $root->fill(['parent_id' => $grandchild->id])->save(), 'parent_id');
        $this->assertNull($root->fresh()->parent_id);
        $root = $root->fresh();
        $root->update(['is_active' => false]);
        $this->assertTrue($child->fresh()->is_active);
        $this->validationRejects(fn () => ProductCategory::create(['code' => 'BAD-PARENT', 'name' => 'Sai cha', 'parent_id' => $root->id, 'is_active' => false]), 'parent_id');
        $child->update(['is_active' => false]);
        $child->update(['name' => 'Nhóm ngừng vẫn sửa tên']);
        $this->validationRejects(fn () => $child->fill(['is_active' => true])->save(), 'parent_id');
        $this->validationRejects(fn () => DecimalId::parse('9223372036854775808', 'id'), 'id');
        $this->validationRejects(fn () => DecimalId::parse('01', 'id'), 'id');
        $this->assertSame('9223372036854775807', DecimalId::parse('9223372036854775807', 'id'));
    }

    public function test_product_domain_decimal_scale_and_inactive_reference_parity(): void
    {
        $product = Product::where('code', 'DEMO-01')->firstOrFail();
        foreach (['sale_price' => '-1.00', 'tax_rate' => '100.01', 'expiry_warning_days' => 3651] as $column => $value) {
            $candidate = $product->fresh()->fill([$column => $value]);
            $this->validationRejects(fn () => $candidate->save(), $column);
        }
        $this->validationRejects(fn () => $product->fresh()->fill(['sale_price' => '1.239'])->save(), 'sale_price');
        DB::table('products')->where('id', $product->id)->update(['sale_price' => '1.239']);
        $this->assertSame('1.24', $product->fresh()->sale_price);
        $product = $product->fresh();
        $product->update(['is_active' => false]);
        $product->category->update(['is_active' => false]);
        $product->unit->update(['is_active' => false]);
        $product->update(['name' => 'Vật tư ngừng hoạt động']);
        $this->assertSame('Vật tư ngừng hoạt động', $product->fresh()->name);
        $this->validationRejects(fn () => $product->fresh()->fill(['category_id' => $product->category_id])->save(), 'category_id');
        $this->validationRejects(fn () => $product->fresh()->fill(['unit_id' => $product->unit_id])->save(), 'unit_id');
        $this->validationRejects(fn () => $product->fresh()->fill(['is_active' => true])->save(), 'category_id');
        $this->validationRejects(fn () => Product::create(['code' => 'BAD-ACTIVE', 'name' => 'Sai nhóm', 'category_id' => $product->category_id, 'unit_id' => $product->unit_id, 'sale_price' => '0', 'is_active' => false]), 'category_id');
    }

    public function test_code_collation_unicode_and_search_wildcards(): void
    {
        Unit::create(['code' => 'kg', 'name' => 'Khác hoa thường']);
        Unit::create(['code' => 'MÃ', 'name' => 'Có dấu']);
        Unit::create(['code' => 'MA', 'name' => 'Không dấu']);
        $this->assertSame(2, Unit::whereIn('code', ['KG', 'kg'])->count());
        $this->assertSame(2, Unit::whereIn('code', ['MÃ', 'MA'])->count());
        $this->assertSame(1, Product::whereRaw('LOWER(name) LIKE ?', ['%hữu cơ%'])->count());
        $this->assertSame(4, Product::whereRaw('LOWER(code) LIKE ?', ['demo-0_'])->count());
    }

    public function test_admin_domain_guards_and_mass_assignment_role(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $this->validationRejects(fn () => $admin->fill(['is_active' => false])->save(), 'role');
        $this->validationRejects(fn () => $admin->fresh()->forceFill(['role' => 'user'])->save(), 'role');
        $this->validationRejects(fn () => $admin->fresh()->delete(), 'role');
        $user = new User(['username' => 'MASS-ASSIGN', 'password_hash' => 'fixture', 'role' => 'admin']);
        $this->assertSame('user', $user->role);
    }

    public function test_isolation_rejects_unapproved_database_host_port_account_and_commands(): void
    {
        $configuration = config('database.connections.mysql');
        foreach (['driver' => 'pgsql', 'database' => 'klcn186_dev', 'host' => '192.0.2.1', 'port' => '3307', 'username' => 'root', 'url' => 'mysql://other.invalid', 'unix_socket' => '/tmp/mysql.sock'] as $key => $value) {
            try {
                RuntimeIsolation::assertDatabaseConfiguration(array_replace($configuration, [$key => $value]));
                $this->fail('Expected isolation rejection.');
            } catch (\LogicException) {
                $this->assertTrue(true);
            }
        }
        foreach ([['artisan', 'migrate:fresh'], ['artisan', 'queue:work'], ['artisan', 'config:show'], ['artisan', 'migrate', '--database=pgsql'], ['artisan', 'db:seed', '--class=DemoDataSeeder'], ['artisan', 'migrate:rollback']] as $arguments) {
            $this->assertFalse(RuntimeIsolation::allowsCommand($arguments));
        }
        $this->assertTrue(RuntimeIsolation::allowsCommand(['artisan', 'migrate', '--force', '--no-interaction']));
    }

    public function test_runtime_account_has_only_schema_crud_and_cannot_create_tables(): void
    {
        $privileges = DB::select("SELECT PRIVILEGE_TYPE FROM information_schema.SCHEMA_PRIVILEGES WHERE TABLE_SCHEMA = DATABASE() AND GRANTEE = CONCAT(CHAR(39), 'mnm_c1_test_runtime', CHAR(39), '@', CHAR(39), 'localhost', CHAR(39)) ORDER BY PRIVILEGE_TYPE");
        $this->assertSame(['DELETE', 'INSERT', 'SELECT', 'UPDATE'], array_column($privileges, 'PRIVILEGE_TYPE'));
        $this->databaseRejects(fn () => DB::statement('CREATE TABLE mnm_permission_probe (id INT)'), 1142);
        $this->assertSame(0, (int) DB::selectOne("SELECT COUNT(*) AS n FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mnm_permission_probe'")->n);
    }

    public function test_eloquent_crud_and_restrict_preserve_references(): void
    {
        $category = ProductCategory::create(['code' => 'CRUD-CATEGORY', 'name' => 'Danh mục kiểm thử']);
        $unit = Unit::create(['code' => 'CRUD-UNIT', 'name' => 'Đơn vị kiểm thử']);
        $product = Product::create(['code' => 'CRUD-PRODUCT', 'name' => 'Vật tư kiểm thử', 'category_id' => $category->id, 'unit_id' => $unit->id, 'sale_price' => '12.34']);
        $product->update(['name' => 'Vật tư đã sửa', 'sale_price' => '23.45']);
        $this->assertSame('Vật tư đã sửa', $product->fresh()->name);
        $this->assertSame('23.45', $product->fresh()->sale_price);
        $this->databaseRejects(fn () => $category->delete(), 1451);
        $this->databaseRejects(fn () => $unit->delete(), 1451);
        $this->assertTrue($product->delete());
        $this->assertTrue($category->delete());
        $this->assertTrue($unit->delete());
        $this->assertNull(Product::find($product->id));
        $this->assertNull(ProductCategory::find($category->id));
        $this->assertNull(Unit::find($unit->id));
    }
}
