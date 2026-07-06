<?php

namespace App\Http\Requests\KamusPokir;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreKamusPokirRequest extends FormRequest
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
            'kamus_version'    => 'required|string|max:50',
            'name'             => 'required|string|max:500',
            'bidang_urusan_id' => 'nullable|exists:bidang_urusans,id',
            'opd_id'           => 'nullable|exists:opds,id',
            'is_active'        => 'boolean',
        ];
    }
}
