<?php

namespace App\Http\Requests\Opd;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOpdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $opdId = $this->route('opd');

        return [
            'code' => [
                'sometimes',
                'string',
                'max:255',
                'unique:opds,code,' . $opdId,
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
