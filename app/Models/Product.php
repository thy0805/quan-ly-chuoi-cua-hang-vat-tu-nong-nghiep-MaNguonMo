<?php

namespace App\Models;

use App\Support\CatalogIntegrity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Product extends CatalogModel
{
    protected $fillable = ['category_id', 'unit_id', 'code', 'name', 'active_ingredient', 'sale_price', 'tax_rate', 'expiry_warning_days', 'is_active'];

    protected array $referenceTouches = [];

    public function stock(): HasOne
    {
        return $this->hasOne(ProductStock::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function saleLines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SaleLine::class);
    }

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
            if ($this->exists && $this->isDirty('unit_id')) {
                $this->newQuery()->lockForUpdate()->findOrFail($this->id);
                if (\Illuminate\Support\Facades\Schema::hasTable('inventory_movements') && $this->inventoryMovements()->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['unit_id' => 'Không thể đổi đơn vị tính của vật tư đã có lịch sử kho.']);
                }
            }
            CatalogIntegrity::product($this, $this->referenceTouches);
            $saved = parent::save($options);
            $this->referenceTouches = [];

            return $saved;
        });
    }
}
