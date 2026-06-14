<?php

namespace App\Http\Requests\Opd;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:opds,code'],
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
