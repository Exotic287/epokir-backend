<?php

namespace App\Http\Requests\Desa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $desaId = $this->route('desa');

        return [
            'code' => [
                'sometimes',
                'string',
                'max:255',
                'unique:desas,code,' . $desaId,
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'kecamatan_id' => ['sometimes', 'integer', 'exists:kecamatans,id'],
            'dapil_id' => ['sometimes', 'integer', 'exists:dapils,id'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ];
    }
}
