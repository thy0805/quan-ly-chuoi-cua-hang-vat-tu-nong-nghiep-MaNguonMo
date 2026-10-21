<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\SaleService;
use App\Support\InventoryTestProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! InventoryTestProfile::active() || DB::selectOne('SELECT DATABASE() AS db')->db !== InventoryTestProfile::DATABASE || config('database.connections.mysql.username') !== 'mnm_c1_test_runtime') throw new \LogicException('C2 demo chỉ chạy trên test database đã được cô lập.');
        DB::transaction(function () {
            $key = '68efcb0c-e846-4549-83cc-199969ae8801';
            $existing = Sale::where('client_key', $key)->first();
            if ($existing) {
                if ($existing->status !== 'confirmed' || $existing->total !== '55000.00' || $existing->lines()->count() !== 2) throw new \LogicException('Demo C2 không khớp; không ghi đè.');
                return;
            }
            if (Product::whereIn('code', ['C2-DEMO-A','C2-DEMO-B'])->exists()) throw new \LogicException('Fixture C2 đã tồn tại một phần; không ghi đè.');
            $admin = User::where('role', 'admin')->firstOrFail();
            $user = User::where('username', 'nhanvien_demo_1')->firstOrFail();
            $sample = Product::where('code', 'DEMO-01')->firstOrFail();
            $rows = [];
            foreach ([['A','10','2','12500.00'],['B','8','1.5','20000.00']] as [$suffix,$initial,$quantity,$price]) {
                $product = Product::create(['code'=>'C2-DEMO-'.$suffix,'name'=>'Vật tư demo bán hàng '.$suffix,'category_id'=>$sample->category_id,'unit_id'=>$sample->unit_id,'sale_price'=>$price]);
                app(InventoryService::class)->move($admin,['product_id'=>$product->id,'direction'=>'in','quantity'=>$initial,'idempotency_key'=>(string) Str::uuid(),'note'=>'Tồn đầu demo C2']);
                $rows[] = ['product_id'=>$product->id,'quantity'=>$quantity,'unit_price'=>$price];
            }
            $sale = app(SaleService::class)->draft($user,['client_key'=>$key,'note'=>'Demo C2: hai vật tư, hóa đơn nội bộ','lines'=>$rows]);
            app(SaleService::class)->confirm($user,$sale->id,$sale->version);
        });
    }
}
