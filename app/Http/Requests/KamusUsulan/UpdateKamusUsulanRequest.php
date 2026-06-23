<?php

namespace App\Http\Requests\KamusUsulan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKamusUsulanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bidang_urusan_id' => ['sometimes', 'integer'],
            'bidang_urusan_baru' => ['nullable', 'string', 'max:255'],
            'uraian_permasalahan' => ['sometimes', 'string', 'max:255'],
            'opd_tujuan' => ['sometimes', 'string', 'max:255'],
            'program' => ['sometimes', 'string', 'max:300'],
            'skema_lokasi' => ['sometimes', 'in:A,B'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }
}
