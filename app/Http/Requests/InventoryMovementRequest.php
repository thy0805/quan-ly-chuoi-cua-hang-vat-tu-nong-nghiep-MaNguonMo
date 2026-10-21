<?php

namespace App\Http\Requests;

use App\Support\DecimalId;
use App\Support\StockQuantity;
use Illuminate\Foundation\Http\FormRequest;

class InventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true && $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return ['product_id' => ['required', 'string', DecimalId::rule()], 'quantity' => ['required', StockQuantity::rule()], 'note' => ['nullable', 'string', 'max:1000'], 'idempotency_key' => ['required', 'uuid'], 'direction' => ['prohibited'], 'user_id' => ['prohibited'], 'source_type' => ['prohibited'], 'source_id' => ['prohibited'], 'quantity_before' => ['prohibited'], 'quantity_after' => ['prohibited']];
    }
}
