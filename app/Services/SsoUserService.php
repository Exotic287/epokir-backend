<?php

namespace App\Services;

use App\Models\User;
use App\Services\Concerns\MakesAuthenticatedSsoRequests;

class SsoUserService
{
    use MakesAuthenticatedSsoRequests;

    /**
     * Daftar user (Dewan/Setwan/Admin) dari SSO Pusat.
     */
    public function getAll(User $admin): array
    {
        return $this->request($admin)->get('/v1/auth/users')->json('data') ?? [];
    }

    public function getById(User $admin, int $id): array
    {
        return $this->request($admin)->get("/v1/auth/users/{$id}")->json('data') ?? [];
    }

    public function create(User $admin, array $data): array
    {
        return $this->request($admin)->post('/v1/auth/users', $data)->json('data') ?? [];
    }

    public function update(User $admin, int $id, array $data): array
    {
        return $this->request($admin)->put("/v1/auth/users/{$id}", $data)->json('data') ?? [];
    }

    public function delete(User $admin, int $id): void
    {
        $this->request($admin)->delete("/v1/auth/users/{$id}");
    }

    public function toggleActive(User $admin, int $id): array
    {
        return $this->request($admin)->patch("/v1/auth/users/{$id}/toggle-active")->json('data') ?? [];
    }
}
