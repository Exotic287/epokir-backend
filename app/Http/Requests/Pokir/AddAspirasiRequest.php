<?php

namespace App\Http\Requests\Pokir;

use Illuminate\Foundation\Http\FormRequest;

class AddAspirasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aspirasi_id' => ['required', 'integer', 'exists:aspirasis,id'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
