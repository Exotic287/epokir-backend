<?php

namespace App\Http\Requests\Aspirasi;

use Illuminate\Foundation\Http\FormRequest;

class StoreAspirasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'desa_id' => ['nullable', 'integer', 'exists:desas,id'],
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatans,id'],
            'dapil_id' => ['nullable', 'integer', 'exists:dapils,id'],
            'opd_id' => ['nullable', 'integer', 'exists:opds,id'],
            'tanggal' => ['required', 'date'],
            'source' => ['required', 'in:reses,tatap_muka,surat,lainnya'],
        ];
    }
}
