<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class User extends Authenticatable
{
    public $timestamps = false;

    protected $keyType = 'string';

    protected $attributes = ['role' => 'user', 'is_active' => true];

    protected $fillable = ['username', 'password_hash', 'is_active'];

    protected $hidden = ['password_hash'];

    public function inventoryMovements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function purchases(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Purchase::class); }

    public function sales(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sale::class);
    }

    protected function casts(): array
    {
        return ['id' => 'string', 'is_active' => 'boolean', 'created_at' => 'immutable_datetime'];
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function save(array $options = []): bool
    {
        return DB::transaction(function () use ($options) {
            self::query()->where('role', 'admin')->lockForUpdate()->get();
            if ($this->exists && $this->getRawOriginal('role') === 'admin'
                && ($this->role !== 'admin' || ! $this->is_active)) {
                throw ValidationException::withMessages(['role' => 'Không được hạ quyền hoặc ngừng Admin cuối cùng.']);
            }
            Validator::make($this->getAttributes(), [
                'username' => ['required', 'string', 'max:80', Rule::unique('users', 'username')->ignore($this->getKey())],
                'password_hash' => ['required', 'string'],
                'role' => ['required', Rule::in(['admin', 'user'])],
                'is_active' => ['required', 'boolean'],
            ])->validate();

            return parent::save($options);
        });
    }

    public function delete()
    {
        if ($this->role === 'admin') {
            throw ValidationException::withMessages(['role' => 'Không được xóa Admin cuối cùng.']);
        }

        return parent::delete();
    }
}
