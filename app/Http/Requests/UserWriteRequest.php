<?php

namespace App\Http\Requests;

use Closure;
use App\Models\User;
use App\Support\DecimalId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserWriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true && $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $record = $this->route('record');
        try {
            $user = $record ? User::findOrFail(DecimalId::parse($record, 'id')) : null;
        } catch (ValidationException) {
            abort(404);
        }

        return ['username' => [$user ? 'sometimes' : 'required', 'required', 'string', 'max:80', Rule::unique('users', 'username')->ignore($user?->id)], 'password' => [$user ? 'nullable' : 'required', 'string', 'min:10', 'max:255', 'confirmed', function (string $attribute, mixed $value, Closure $fail) {
            if (is_string($value) && strlen($value) > 72) {
                $fail('Mật khẩu vượt giới hạn 72 byte của bcrypt.');
            }
        }], 'is_active' => ['sometimes', 'required', 'boolean'], 'role' => ['sometimes', Rule::in([$user?->role ?? 'user'])], 'password_hash' => ['prohibited']];
    }
}
