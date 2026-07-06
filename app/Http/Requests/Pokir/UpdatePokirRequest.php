<?php

namespace App\Http\Requests\Pokir;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePokirRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => ['sometimes', 'string', 'max:255'],
            'kamus_pokir_id' => ['nullable', 'integer', 'exists:kamus_pokirs,id'],
            'opd_id'         => ['nullable', 'integer', 'exists:opds,id'],
            'dapil_id'       => ['nullable', 'integer', 'exists:dapils,id'],
            'kecamatan_ids'  => ['nullable', 'array'],
            'kecamatan_ids.*'=> ['integer', 'exists:kecamatans,id'],
            'desa_ids'       => ['nullable', 'array'],
            'desa_ids.*'     => ['integer', 'exists:desas,id'],
            'notes'          => ['nullable', 'string'],
            'volume'         => ['nullable', 'numeric', 'min:0'],
            'satuan'         => ['nullable', 'string', 'max:50'],
            'satuan_custom'  => ['nullable', 'string', 'max:100'],
            'aspirasi_ids'   => ['nullable', 'array'],
            'aspirasi_ids.*' => ['integer', 'exists:aspirasis,id'],
        ];
    }
}
