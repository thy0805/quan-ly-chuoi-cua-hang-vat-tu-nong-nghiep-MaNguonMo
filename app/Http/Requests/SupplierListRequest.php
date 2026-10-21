<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierListRequest extends FormRequest
{
    public function authorize(): bool { return (bool) $this->user()?->is_active; }
    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:100'], 'is_active' => ['nullable', 'boolean'], 'per_page' => ['nullable', 'integer', 'between:1,50'], 'page' => ['nullable', 'integer', 'min:1']];
    }
}
