<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = ['product_id', 'quantity', 'updated_at'];

    protected function casts(): array
    {
        return ['id' => 'string', 'product_id' => 'string', 'quantity' => 'decimal:3', 'updated_at' => 'immutable_datetime'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
