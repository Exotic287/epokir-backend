<?php

namespace App\Http\Requests\Periode;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['sometimes', 'string', 'max:255'],
            'tanggal_buka' => ['sometimes', 'date'],
            'batas_submit' => ['sometimes', 'date', 'after:tanggal_buka'],
            'jadwal_freeze' => ['sometimes', 'date'],
        ];
    }
}
