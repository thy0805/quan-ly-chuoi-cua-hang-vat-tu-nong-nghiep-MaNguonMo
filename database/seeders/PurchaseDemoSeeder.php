<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\PurchaseService;
use App\Support\InventoryTestProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! InventoryTestProfile::active() || DB::selectOne('SELECT DATABASE() AS db')->db !== InventoryTestProfile::DATABASE) throw new \LogicException('C3 demo requires the isolated test profile.');
        DB::transaction(function () {
            $admin = User::where('username', 'admin_demo')->firstOrFail();
            $sample = Product::where('code', 'DEMO-01')->firstOrFail();
            $supplier = Supplier::firstOrCreate(['code' => 'C3-DEMO-NCC'], ['name' => 'Nhà cung cấp minh họa Nhóm 6', 'address' => 'Địa chỉ minh họa', 'phone' => '0900000000']);
            $rows = [];
            foreach ([['C3-DEMO-A', '20', '12500'], ['C3-DEMO-B', '8.5', '20000']] as [$code, $quantity, $price]) {
                $product = Product::firstOrCreate(['code' => $code], ['name' => 'Vật tư minh họa nhập hàng '.$code, 'category_id' => $sample->category_id, 'unit_id' => $sample->unit_id, 'sale_price' => '25000']);
                $rows[] = ['product_id' => $product->id, 'quantity' => $quantity, 'unit_price' => $price];
            }
            $service = app(PurchaseService::class);
            $purchase = $service->draft($admin, ['supplier_id' => $supplier->id, 'client_key' => 'c3000000-0000-4000-8000-000000000001', 'note' => 'Fixture C3 riêng, không ghi database nghiệp vụ.', 'lines' => $rows]);
            $service->confirm($admin, $purchase->id, 1);
        }, 3);
    }
}
