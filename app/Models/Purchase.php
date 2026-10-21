<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Purchase extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';

    protected function casts(): array
    {
        return ['id' => 'string', 'user_id' => 'string', 'supplier_id' => 'string', 'version' => 'integer', 'total' => 'decimal:2', 'confirmed_at' => 'immutable_datetime'];
    }

    public function lines(): HasMany { return $this->hasMany(PurchaseLine::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function getNumberAttribute(): string { return 'PN-'.str_pad($this->id, 8, '0', STR_PAD_LEFT); }

    protected static function booted(): void
    {
        static::saving(function (Purchase $purchase) {
            if ($purchase->exists && $purchase->getRawOriginal('status') === 'confirmed' && $purchase->isDirty()) {
                throw ValidationException::withMessages(['purchase' => 'Không được sửa phiếu nhập đã xác nhận.']);
            }
        });
        static::deleting(function (Purchase $purchase) {
            if ($purchase->status === 'confirmed') throw ValidationException::withMessages(['purchase' => 'Không được xóa phiếu nhập đã xác nhận.']);
        });
    }
}
