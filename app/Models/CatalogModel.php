<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class CatalogModel extends Model
{
    public $timestamps = false;

    protected $keyType = 'string';

    protected $attributes = ['is_active' => true];

    protected function casts(): array
    {
        return ['id' => 'string', 'is_active' => 'boolean'];
    }
}
