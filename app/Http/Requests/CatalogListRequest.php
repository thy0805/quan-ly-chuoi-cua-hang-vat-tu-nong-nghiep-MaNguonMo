<?php

namespace App\Http\Requests;

use App\Support\DecimalId;
use Illuminate\Foundation\Http\FormRequest;

class CatalogListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true;
    }

    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:160'], 'page' => ['sometimes', 'integer', 'min:1', 'max:1000000'], 'per_page' => ['nullable', 'integer', 'min:1', 'max:50'], 'active_only' => ['nullable', 'boolean'], 'is_active' => ['nullable', 'boolean'], 'category_id' => ['nullable', 'string', DecimalId::rule()], 'unit_id' => ['nullable', 'string', DecimalId::rule()]];
    }
}
