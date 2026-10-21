<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class PurchaseLine extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $keyType = 'string';

    protected function casts(): array
    {
        return ['id' => 'string', 'purchase_id' => 'string', 'product_id' => 'string', 'quantity' => 'decimal:3', 'unit_price' => 'decimal:2', 'line_total' => 'decimal:2'];
    }

    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }

    protected static function booted(): void
    {
        $check = static function (PurchaseLine $line) {
            if (Purchase::whereKey($line->getRawOriginal('purchase_id') ?? $line->purchase_id)->value('status') === 'confirmed') {
                throw ValidationException::withMessages(['purchase' => 'Dòng phiếu nhập đã xác nhận không được thay đổi.']);
            }
        };
        static::saving($check);
        static::deleting($check);
    }
}
