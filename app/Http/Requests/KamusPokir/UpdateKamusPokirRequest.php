<?php

namespace App\Http\Requests\KamusPokir;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKamusPokirRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kamus_version' => 'sometimes|string|max:255',
            'level' => 'sometimes|integer',
            'parent_id' => 'nullable|exists:kamus_pokirs,id',
            'opd_id' => 'sometimes|exists:opds,id',
            'program_sipd_id' => 'sometimes|exists:program_sipds,id',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
