<?php

namespace App\Http\Requests\Aspirasi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAspirasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'desa_id' => ['nullable', 'integer', 'exists:desas,id'],
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatans,id'],
            'dapil_id' => ['nullable', 'integer', 'exists:dapils,id'],
            'opd_id' => ['nullable', 'integer', 'exists:opds,id'],
            'tanggal' => ['sometimes', 'date'],
            'source' => ['sometimes', 'in:reses,tatap_muka,surat,lainnya'],
            'is_complete' => ['sometimes', 'boolean'],
        ];
    }
}
