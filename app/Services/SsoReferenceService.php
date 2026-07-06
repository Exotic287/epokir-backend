<?php

namespace App\Services;

use App\Models\Dapil;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\User;
use App\Services\Concerns\MakesAuthenticatedSsoRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * CRUD Dapil & Fraksi — proxy ke SSO Pusat. Ini BUKAN tabel dapils lokal
 * E-Pokir (lihat MasterDataService::*Dapil), melainkan master data Dapil &
 * Fraksi milik SSO Pusat yang dipakai untuk assign dapil_id/fraksi_id user.
 *
 * Field wilayah di sini disinkronkan ke Kecamatan/Desa lokal (dicocokkan lewat
 * nama Dapil, sama seperti AuthService::resolveLocalDapilId) supaya scoping
 * lokasi Aspirasi ikut berubah saat admin mengubah wilayah sebuah Dapil.
 */
class SsoReferenceService
{
    use MakesAuthenticatedSsoRequests;

    // ─── Dapil ──────────────────────────────────────────────────────────────

    public function getDapils(User $admin): array
    {
        return $this->request($admin)->get('/v1/auth/dapils')->json('data') ?? [];
    }

    public function getDapilById(User $admin, int $id): array
    {
        return $this->request($admin)->get("/v1/auth/dapils/{$id}")->json('data') ?? [];
    }

    public function createDapil(User $admin, array $data): array
    {
        $result = $this->request($admin)->post('/v1/auth/dapils', $data)->json('data') ?? [];
        $this->claimWilayah($admin, $result['id'] ?? null, $result['nama'] ?? $data['nama'] ?? null, $data['wilayah'] ?? null);

        return $result;
    }

    public function updateDapil(User $admin, int $id, array $data): array
    {
        $result = $this->request($admin)->put("/v1/auth/dapils/{$id}", $data)->json('data') ?? [];
        $this->claimWilayah($admin, $id, $result['nama'] ?? $data['nama'] ?? null, $data['wilayah'] ?? null);

        return $result;
    }

    /**
     * Dapil yang baru disimpan ini menang atas kecamatan yang disebut di
     * wilayah-nya: kecamatan tsb dicopot dari wilayah Dapil SSO lain yang masih
     * menyebutnya (supaya tidak ada klaim ganda di teks), lalu Kecamatan/Desa
     * lokal disinkron ke Dapil lokal yang nama-nya cocok dengan Dapil ini.
     */
    private function claimWilayah(User $admin, ?int $dapilId, ?string $dapilNama, ?string $wilayah): void
    {
        $claimedNames = collect(explode(',', (string) $wilayah))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->values();

        if ($claimedNames->isEmpty()) {
            return;
        }

        $claimedUpper = $claimedNames->map(fn ($name) => mb_strtoupper($name))->all();

        // 1. Copot kecamatan yg baru diklaim dari wilayah Dapil SSO lain yang masih menyebutnya
        try {
            $allDapils = $this->getDapils($admin);
        } catch (\Exception) {
            $allDapils = [];
        }

        foreach ($allDapils as $other) {
            if (($other['id'] ?? null) === $dapilId || empty($other['wilayah'])) {
                continue;
            }

            $otherNames = collect(explode(',', $other['wilayah']))->map(fn ($name) => trim($name))->filter();
            $remaining = $otherNames->reject(fn ($name) => in_array(mb_strtoupper($name), $claimedUpper, true));

            if ($remaining->count() === $otherNames->count()) {
                continue;
            }

            $this->request($admin)->put("/v1/auth/dapils/{$other['id']}", [
                'wilayah' => $remaining->implode(', '),
            ]);
        }

        // 2. Sinkron Kecamatan/Desa lokal ke Dapil lokal yang nama-nya cocok dengan Dapil ini
        $localDapil = $dapilNama ? Dapil::where('name', $dapilNama)->first() : null;
        if (! $localDapil) {
            return;
        }

        $kecamatanIds = Kecamatan::whereIn(DB::raw('UPPER(name)'), $claimedUpper)->pluck('id');
        if ($kecamatanIds->isEmpty()) {
            return;
        }

        Kecamatan::whereIn('id', $kecamatanIds)->update(['dapil_id' => $localDapil->id]);
        Desa::whereIn('kecamatan_id', $kecamatanIds)->update(['dapil_id' => $localDapil->id]);

        Cache::flush();
    }

    public function deleteDapil(User $admin, int $id): void
    {
        $this->request($admin)->delete("/v1/auth/dapils/{$id}");
    }

    // ─── Fraksi ─────────────────────────────────────────────────────────────

    public function getFraksis(User $admin): array
    {
        return $this->request($admin)->get('/v1/auth/fraksis')->json('data') ?? [];
    }

    public function getFraksiById(User $admin, int $id): array
    {
        return $this->request($admin)->get("/v1/auth/fraksis/{$id}")->json('data') ?? [];
    }

    public function createFraksi(User $admin, array $data): array
    {
        return $this->request($admin)->post('/v1/auth/fraksis', $data)->json('data') ?? [];
    }

    public function updateFraksi(User $admin, int $id, array $data): array
    {
        return $this->request($admin)->put("/v1/auth/fraksis/{$id}", $data)->json('data') ?? [];
    }

    public function deleteFraksi(User $admin, int $id): void
    {
        $this->request($admin)->delete("/v1/auth/fraksis/{$id}");
    }
}
