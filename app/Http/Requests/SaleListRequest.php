<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleListRequest extends FormRequest
{
    public function authorize(): bool { return (bool) $this->user()?->is_active; }
    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in(['draft', 'confirmed'])], 'from' => ['nullable', 'date_format:Y-m-d'], 'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'], 'per_page' => ['nullable', 'integer', 'between:1,50'], 'page' => ['nullable', 'integer', 'min:1']];
    }
}
