<?php

namespace App\Models;

use App\Support\CatalogIntegrity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends CatalogModel
{
    protected $fillable = ['code', 'name', 'is_active'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function save(array $options = []): bool
    {
        CatalogIntegrity::unit($this);

        return parent::save($options);
    }
}
