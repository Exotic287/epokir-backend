<?php

namespace App\Http\Requests\Dapil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDapilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dapilId = $this->route('dapil');

        return [
            'number' => [
                'sometimes',
                'integer',
                'unique:dapils,number,' . $dapilId,
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
