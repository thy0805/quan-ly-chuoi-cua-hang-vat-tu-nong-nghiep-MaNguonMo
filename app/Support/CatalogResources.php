<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;

final class CatalogResources
{
    public static function definition(string $name): array
    {
        return match ($name) {
            'categories' => ['model' => ProductCategory::class, 'title' => 'Danh mục vật tư', 'search' => ['code', 'name'], 'relations' => ['parent'], 'fields' => ['code' => 'Mã danh mục', 'name' => 'Tên danh mục', 'parent_id' => 'Danh mục cha', 'is_active' => 'Đang hoạt động']],
            'units' => ['model' => Unit::class, 'title' => 'Đơn vị tính', 'search' => ['code', 'name'], 'relations' => [], 'fields' => ['code' => 'Mã đơn vị', 'name' => 'Tên đơn vị', 'is_active' => 'Đang hoạt động']],
            'products' => ['model' => Product::class, 'title' => 'Vật tư', 'search' => ['code', 'name', 'active_ingredient'], 'relations' => ['category', 'unit'], 'fields' => ['code' => 'Mã vật tư', 'name' => 'Tên vật tư', 'category_id' => 'Danh mục', 'unit_id' => 'Đơn vị tính', 'active_ingredient' => 'Hoạt chất', 'sale_price' => 'Giá bán', 'tax_rate' => 'Thuế (%)', 'expiry_warning_days' => 'Số ngày cảnh báo hạn', 'is_active' => 'Đang hoạt động']],
            default => abort(404),
        };
    }
}
