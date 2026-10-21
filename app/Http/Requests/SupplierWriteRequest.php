<?php

namespace App\Http\Requests;

use App\Support\DecimalId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierWriteRequest extends FormRequest
{
    public function authorize(): bool { return (bool) ($this->user()?->is_active && $this->user()?->role === 'admin'); }

    protected function prepareForValidation(): void
    {
        $values = [];
        foreach (['code', 'name', 'phone', 'email', 'address'] as $key) {
            if (is_string($this->input($key))) $values[$key] = trim($this->input($key));
        }
        $this->merge($values);
    }

    public function rules(): array
    {
        $unique = Rule::unique('suppliers', 'code');
        if ($this->route('supplier')) $unique->ignore(DecimalId::parse((string) $this->route('supplier'), 'supplier'));
        return ['code' => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9][A-Za-z0-9_-]*$/D', $unique], 'name' => ['required', 'string', 'max:200'], 'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+() .-]+$/D'], 'email' => ['nullable', 'email', 'max:200'], 'address' => ['nullable', 'string', 'max:500'], 'is_active' => ['required', 'boolean']];
    }
}
