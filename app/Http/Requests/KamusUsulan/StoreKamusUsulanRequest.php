<?php

namespace App\Http\Requests\KamusUsulan;

use Illuminate\Foundation\Http\FormRequest;

class StoreKamusUsulanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bidang_urusan_id' => ['required', 'integer'],
            'bidang_urusan_baru' => ['nullable', 'string', 'max:255'],
            'uraian_permasalahan' => ['required', 'string', 'max:255'],
            'opd_tujuan' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:300'],
            'skema_lokasi' => ['required', 'in:A,B'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
