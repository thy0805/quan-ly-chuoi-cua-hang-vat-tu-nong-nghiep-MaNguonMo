<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = getenv('MNM_DEMO_ADMIN_PASSWORD');
        $userPassword = getenv('MNM_DEMO_USER_PASSWORD');
        if (! is_string($adminPassword) || strlen($adminPassword) < 16
            || ! is_string($userPassword) || strlen($userPassword) < 16) {
            throw new RuntimeException('MNM: cần cung cấp mật khẩu demo riêng, tối thiểu 16 ký tự, trước khi seed.');
        }
        DB::transaction(function () use ($adminPassword, $userPassword) {
            foreach ([User::class, ProductCategory::class, Unit::class, Product::class] as $model) {
                if ($model::query()->exists()) {
                    throw new RuntimeException('MNM: seeder chỉ nhận schema demo rỗng; không ghi đè dữ liệu hiện hữu.');
                }
            }
            (new User)->forceFill(['username' => 'admin_demo', 'password_hash' => Hash::make($adminPassword), 'role' => 'admin', 'is_active' => true])->save();
            foreach (['nhanvien_demo_1', 'nhanvien_demo_2'] as $username) {
                User::create(['username' => $username, 'password_hash' => Hash::make($userPassword), 'is_active' => true]);
            }
            $kg = Unit::create(['code' => 'KG', 'name' => 'Kilôgam']);
            $bottle = Unit::create(['code' => 'CHAI', 'name' => 'Chai']);
            Unit::create(['code' => 'GOI', 'name' => 'Gói']);
            $fertilizer = ProductCategory::create(['code' => 'PHANBON', 'name' => 'Phân bón']);
            $organic = ProductCategory::create(['code' => 'HUUCO', 'name' => 'Phân hữu cơ', 'parent_id' => $fertilizer->getKey()]);
            $mineral = ProductCategory::create(['code' => 'VOSINH', 'name' => 'Phân vô cơ', 'parent_id' => $fertilizer->getKey()]);
            $protection = ProductCategory::create(['code' => 'BVTV', 'name' => 'Bảo vệ thực vật']);
            ProductCategory::create(['code' => 'NGUNG', 'name' => 'Nhóm demo ngừng hoạt động', 'is_active' => false]);
            $fixtures = [
                ['DEMO-01', 'Phân bón hữu cơ 🌱', $organic, $kg, '0.00', null, null],
                ['DEMO-02', 'Phân NPK mẫu tổng hợp', $mineral, $kg, '19.99', '5.00', 30],
                ['DEMO-03', 'Mẫu kiểm biên giá và thuế', $mineral, $kg, '9999999999999999.99', '100.00', 3650],
                ['DEMO-04', 'Dung dịch bảo vệ cây mẫu', $protection, $bottle, '125000.50', '0.00', 0],
            ];
            foreach ($fixtures as [$code, $name, $category, $unit, $price, $tax, $expiry]) {
                Product::create(['code' => $code, 'name' => $name, 'category_id' => $category->getKey(), 'unit_id' => $unit->getKey(), 'sale_price' => $price, 'tax_rate' => $tax, 'expiry_warning_days' => $expiry, 'active_ingredient' => null]);
            }
        });
    }
}
