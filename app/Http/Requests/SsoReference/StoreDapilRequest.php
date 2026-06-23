<?php

namespace App\Http\Requests\SsoReference;

use Illuminate\Foundation\Http\FormRequest;

class StoreDapilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'wilayah' => 'nullable|string',
            'jumlah_kursi' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama dapil wajib diisi.',
            'jumlah_kursi.required' => 'Jumlah kursi wajib diisi.',
            'jumlah_kursi.min' => 'Jumlah kursi minimal 1.',
        ];
    }
}
