<?php

namespace App\Http\Requests\Kecamatan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKecamatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kecamatanId = $this->route('kecamatan');

        return [
            'code' => [
                'sometimes',
                'string',
                'max:255',
                'unique:kecamatans,code,' . $kecamatanId,
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'dapil_id' => ['sometimes', 'integer', 'exists:dapils,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
