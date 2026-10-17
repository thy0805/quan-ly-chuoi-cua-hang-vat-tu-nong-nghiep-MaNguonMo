<?php

namespace App\Http\Requests;

use App\Support\DecimalId;
use App\Support\DecimalLimit;
use Illuminate\Foundation\Http\FormRequest;

class CatalogWriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true && $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $create = $this->isMethod('POST');
        $presence = $create ? 'required' : 'sometimes';
        $product = $this->route('catalog') === 'products';
        $rules = ['code' => [$presence, 'required', 'string', 'max:'.($product ? 60 : 40)], 'name' => [$presence, 'required', 'string', 'max:'.match ($this->route('catalog')) {'products' => 200, 'categories' => 160, default => 100}], 'is_active' => ['sometimes', 'required', 'boolean']];
        if ($this->route('catalog') === 'categories') {
            $rules['parent_id'] = ['sometimes', 'nullable', 'string', DecimalId::rule()];
        }
        if ($product) {
            $rules += ['category_id' => [$presence, 'required', 'string', DecimalId::rule()], 'unit_id' => [$presence, 'required', 'string', DecimalId::rule()], 'active_ingredient' => ['sometimes', 'nullable', 'string', 'max:200'], 'sale_price' => [$presence, 'required', 'numeric', 'min:0', 'decimal:0,2', DecimalLimit::rule(16, 2)], 'tax_rate' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100', 'decimal:0,2'], 'expiry_warning_days' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:3650']];
        }

        return $rules;
    }
}
