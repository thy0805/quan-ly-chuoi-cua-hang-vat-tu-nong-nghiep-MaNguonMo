<?php

namespace App\Models;

use App\Support\CatalogIntegrity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ProductCategory extends CatalogModel
{
    protected $fillable = ['code', 'name', 'parent_id', 'is_active'];

    protected function casts(): array
    {
        return [...parent::casts(), 'parent_id' => 'string'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function save(array $options = []): bool
    {
        return DB::transaction(function () use ($options) {
            CatalogIntegrity::category($this);

            return parent::save($options);
        });
    }
}
