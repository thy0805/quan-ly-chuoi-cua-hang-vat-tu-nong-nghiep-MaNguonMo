<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Sale extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';

    protected function casts(): array
    {
        return ['id' => 'string', 'user_id' => 'string', 'version' => 'integer', 'total' => 'decimal:2', 'confirmed_at' => 'immutable_datetime'];
    }

    public function lines(): HasMany { return $this->hasMany(SaleLine::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function getNumberAttribute(): string { return 'BH-'.str_pad($this->id, 8, '0', STR_PAD_LEFT); }

    protected static function booted(): void
    {
        static::saving(function (Sale $sale) {
            if ($sale->exists && $sale->getRawOriginal('status') === 'confirmed' && $sale->isDirty()) {
                throw ValidationException::withMessages(['sale' => 'Không được sửa hóa đơn đã xác nhận.']);
            }
        });
        static::deleting(function (Sale $sale) {
            if ($sale->status === 'confirmed') throw ValidationException::withMessages(['sale' => 'Không được xóa hóa đơn đã xác nhận.']);
        });
    }
}
