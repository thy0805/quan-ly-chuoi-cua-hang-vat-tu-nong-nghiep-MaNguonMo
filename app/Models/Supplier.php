<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends CatalogModel
{
    protected $fillable = ['code', 'name', 'phone', 'email', 'address', 'is_active'];

    public function purchases(): HasMany { return $this->hasMany(Purchase::class); }
}
