<?php

namespace App\Http\Requests\Periode;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_buka' => ['required', 'date'],
            'batas_submit' => ['required', 'date', 'after:tanggal_buka'],
            'jadwal_freeze' => ['required', 'date'],
        ];
    }
}
