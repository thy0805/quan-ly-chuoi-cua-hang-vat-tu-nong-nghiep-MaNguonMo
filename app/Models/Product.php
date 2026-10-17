<?php

namespace App\Models;

use App\Support\CatalogIntegrity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Product extends CatalogModel
{
    protected $fillable = ['category_id', 'unit_id', 'code', 'name', 'active_ingredient', 'sale_price', 'tax_rate', 'expiry_warning_days', 'is_active'];

    protected array $referenceTouches = [];

    protected function casts(): array
    {
        return [...parent::casts(), 'category_id' => 'string', 'unit_id' => 'string', 'sale_price' => 'decimal:2', 'tax_rate' => 'decimal:2', 'expiry_warning_days' => 'integer'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, ['category_id', 'unit_id'], true)) {
            $this->referenceTouches[$key] = true;
        }

        return parent::setAttribute($key, $value);
    }

    public function save(array $options = []): bool
    {
        return DB::transaction(function () use ($options) {
            CatalogIntegrity::product($this, $this->referenceTouches);
            $saved = parent::save($options);
            $this->referenceTouches = [];

            return $saved;
        });
    }
}
