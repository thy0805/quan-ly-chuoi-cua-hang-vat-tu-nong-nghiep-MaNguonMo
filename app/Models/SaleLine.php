<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class SaleLine extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $keyType = 'string';

    protected function casts(): array
    {
        return ['id' => 'string', 'sale_id' => 'string', 'product_id' => 'string', 'quantity' => 'decimal:3', 'unit_price' => 'decimal:2', 'line_total' => 'decimal:2'];
    }

    public function sale(): BelongsTo { return $this->belongsTo(Sale::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }

    protected static function booted(): void
    {
        $check = static function (SaleLine $line) {
            if (Sale::whereKey($line->getRawOriginal('sale_id') ?? $line->sale_id)->value('status') === 'confirmed') {
                throw ValidationException::withMessages(['sale' => 'Dòng hóa đơn đã xác nhận không được thay đổi.']);
            }
        };
        static::saving($check);
        static::deleting($check);
    }
}
