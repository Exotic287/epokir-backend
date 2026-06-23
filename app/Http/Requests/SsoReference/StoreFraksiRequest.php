<?php

namespace App\Http\Requests\SsoReference;

use Illuminate\Foundation\Http\FormRequest;

class StoreFraksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'partai' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama fraksi wajib diisi.',
        ];
    }
}
