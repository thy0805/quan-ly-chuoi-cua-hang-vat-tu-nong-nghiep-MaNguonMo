<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class CatalogIntegrity
{
    public static function category(ProductCategory $category): void
    {
        self::base($category, 40, 160, 'product_categories');
        $categories = ProductCategory::query()->orderBy('id')->lockForUpdate()->get(['id', 'parent_id', 'is_active'])->keyBy('id');
        $parentId = $category->parent_id;
        if ($parentId !== null) {
            DecimalId::parse($parentId, 'parent_id');
            $parent = $categories->get($parentId);
            if ($parent === null || ((! $category->exists || $category->is_active) && ! $parent->is_active)) {
                throw ValidationException::withMessages(['parent_id' => 'Nhóm cha phải còn hoạt động.']);
            }
        }
        $seen = $category->getKey() === null ? [] : [$category->getKey() => true];
        while ($parentId !== null) {
            if (isset($seen[$parentId])) {
                throw ValidationException::withMessages(['parent_id' => 'Nhóm cha tạo thành chu kỳ.']);
            }
            $seen[$parentId] = true;
            $parentId = $categories->get($parentId)?->parent_id;
        }
    }

    public static function unit(Unit $unit): void
    {
        self::base($unit, 40, 100, 'units');
    }

    public static function product(Product $product, array $touches): void
    {
        self::base($product, 60, 200, 'products');
        $attributes = $product->getAttributes();
        $attributes['category_id'] = $product->category_id;
        $attributes['unit_id'] = $product->unit_id;
        Validator::make($attributes, [
            'category_id' => ['required', 'string', DecimalId::rule()],
            'unit_id' => ['required', 'string', DecimalId::rule()],
            'active_ingredient' => ['nullable', 'string', 'max:200'],
            'sale_price' => ['required', 'numeric', 'min:0', 'decimal:0,2', DecimalLimit::rule(16, 2)],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
            'expiry_warning_days' => ['nullable', 'integer', 'min:0', 'max:3650'],
        ])->validate();
        foreach (['category_id' => ProductCategory::class, 'unit_id' => Unit::class] as $key => $model) {
            $reference = $model::query()->lockForUpdate()->find($product->{$key});
            if ($reference === null || ((! $product->exists || $product->is_active || isset($touches[$key])) && ! $reference->is_active)) {
                throw ValidationException::withMessages([$key => 'Chỉ được chọn dữ liệu còn hoạt động.']);
            }
        }
    }

    private static function base(ProductCategory|Unit|Product $model, int $codeLength, int $nameLength, string $table): void
    {
        Validator::make($model->getAttributes(), [
            'code' => ['required', 'string', 'max:'.$codeLength, Rule::unique($table, 'code')->ignore($model->getKey())],
            'name' => ['required', 'string', 'max:'.$nameLength],
            'is_active' => ['required', 'boolean'],
        ])->validate();
        $model->code = trim($model->code);
        $model->name = trim($model->name);
    }
}
