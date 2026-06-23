<?php

namespace App\Services;

use App\Models\User;
use App\Services\Concerns\MakesAuthenticatedSsoRequests;

/**
 * CRUD Dapil & Fraksi — proxy ke SSO Pusat. Ini BUKAN tabel dapils lokal
 * E-Pokir (lihat MasterDataService::*Dapil), melainkan master data Dapil &
 * Fraksi milik SSO Pusat yang dipakai untuk assign dapil_id/fraksi_id user.
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
        return $this->request($admin)->post('/v1/auth/dapils', $data)->json('data') ?? [];
    }

    public function updateDapil(User $admin, int $id, array $data): array
    {
        return $this->request($admin)->put("/v1/auth/dapils/{$id}", $data)->json('data') ?? [];
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
