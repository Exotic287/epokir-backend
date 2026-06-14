<?php

namespace App\Http\Requests\Kecamatan;

use Illuminate\Foundation\Http\FormRequest;

class StoreKecamatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:kecamatans,code',
            ],
            'name' => ['required', 'string', 'max:255'],
            'dapil_id' => ['required', 'integer', 'exists:dapils,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
