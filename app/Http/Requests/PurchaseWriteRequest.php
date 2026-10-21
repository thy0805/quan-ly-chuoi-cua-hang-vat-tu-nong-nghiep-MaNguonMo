<?php

namespace App\Http\Requests;

use App\Services\PurchaseService;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseWriteRequest extends FormRequest
{
    public function authorize(): bool { return (bool) ($this->user()?->is_active && $this->user()?->role === 'admin'); }
    public function rules(): array
    {
        return [...PurchaseService::rules(), ...($this->isMethod('PATCH') ? ['version' => ['required', 'integer', 'min:1', 'max:4294967294']] : [])];
    }
}
