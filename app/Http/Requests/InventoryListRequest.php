<?php

namespace App\Http\Requests;

use App\Support\DecimalId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true;
    }

    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:160'], 'product_id' => ['nullable', 'string', DecimalId::rule()], 'category_id' => ['nullable', 'string', DecimalId::rule()], 'direction' => ['nullable', Rule::in(['in', 'out'])], 'stock_status' => ['nullable', Rule::in(['positive', 'zero'])], 'from' => ['nullable', 'date_format:Y-m-d'], 'to' => ['nullable', 'date_format:Y-m-d', Rule::when($this->filled('from'), ['after_or_equal:from'])], 'page' => ['sometimes', 'integer', 'min:1', 'max:1000000'], 'per_page' => ['nullable', 'integer', 'min:1', 'max:50']];
    }
}
