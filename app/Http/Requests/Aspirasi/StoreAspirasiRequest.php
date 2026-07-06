<?php

namespace App\Http\Requests\Aspirasi;

use Illuminate\Foundation\Http\FormRequest;

class StoreAspirasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Lenient secara sengaja — form workspace progresif boleh "Simpan Draft" sebelum
            // semua field terisi. Kelengkapan sesungguhnya dihitung di AspirasiService::computeIsComplete().
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'desa_id' => ['nullable', 'integer', 'exists:desas,id'],
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatans,id'],
            'dapil_id' => ['nullable', 'integer', 'exists:dapils,id'],
            'opd_id' => ['nullable', 'integer', 'exists:opds,id'],
            'kamus_usulan_id' => ['nullable', 'integer', 'exists:kamus_usulans,id'],
            'tanggal' => ['nullable', 'date'],
            'source' => ['nullable', 'in:rdp_audiensi,kunjungan_kerja,rapat_mitra,musrenbang,e_reses'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'narasi_dokumen' => ['nullable', 'string'],
        ];
    }
}
