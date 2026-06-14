<?php

namespace App\Http\Requests\Desa;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:desas,code'],
            'name' => ['required', 'string', 'max:255'],
            'kecamatan_id' => ['required', 'integer', 'exists:kecamatans,id'],
            'dapil_id' => ['required', 'integer', 'exists:dapils,id'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ];
    }
}
