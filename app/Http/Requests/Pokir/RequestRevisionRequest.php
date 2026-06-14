<?php

namespace App\Http\Requests\Pokir;

use Illuminate\Foundation\Http\FormRequest;

class RequestRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flags' => ['required', 'array', 'min:1'],
            'flags.*.field_name' => ['required', 'string'],
            'flags.*.note' => ['nullable', 'string'],
        ];
    }
}
