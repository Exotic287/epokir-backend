<?php

namespace App\Http\Requests\SsoReference;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFraksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'sometimes|string|max:255',
            'partai' => 'nullable|string|max:100',
        ];
    }
}
