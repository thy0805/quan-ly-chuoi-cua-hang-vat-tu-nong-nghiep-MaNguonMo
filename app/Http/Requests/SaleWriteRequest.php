<?php

namespace App\Http\Requests;

use App\Services\SaleService;
use Illuminate\Foundation\Http\FormRequest;

class SaleWriteRequest extends FormRequest
{
    public function authorize(): bool { return (bool) $this->user()?->is_active; }
    public function rules(): array
    {
        return [...SaleService::rules(), ...($this->isMethod('PATCH') ? ['version' => ['required', 'integer', 'min:1', 'max:4294967294']] : [])];
    }
}
