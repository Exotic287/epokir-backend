<?php

namespace App\Services;

use App\Models\Aspirasi;
use App\Models\AspirasiActivity;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AspirasiService
{
    /**
     * Daftar aspirasi dengan filter dan paginasi.
     */
    public function index(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Aspirasi::with(['user', 'desa', 'kecamatan', 'dapil', 'opd'])
            ->latest();

        // Dewan hanya bisa lihat aspirasi miliknya sendiri
        if ($user->isDewan()) {
            $query->where('user_id', $user->id);
        }

        if (!empty($filters['opd_id'])) {
            $query->where('opd_id', $filters['opd_id']);
        }
        if (!empty($filters['dapil_id'])) {
            $query->where('dapil_id', $filters['dapil_id']);
        }
        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }
        if (isset($filters['is_complete'])) {
            $query->where('is_complete', (bool) $filters['is_complete']);
        }
        if (!empty($filters['tahun'])) {
            $query->whereYear('tanggal', (int) $filters['tahun']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Buat aspirasi baru.
     */
    public function store(User $user, array $data): Aspirasi
    {
        $data['user_id'] = $user->id;
        $data['code']    = $this->generateCode();

        $aspirasi = Aspirasi::create($data);

        $this->logActivity($aspirasi, $user, 'created');

        return $aspirasi->load(['user', 'desa', 'kecamatan', 'dapil', 'opd']);
    }

    /**
     * Detail aspirasi.
     */
    public function show(User $user, int $id): Aspirasi
    {
        $aspirasi = Aspirasi::with([
            'user', 'desa', 'kecamatan', 'dapil', 'opd',
            'attachments', 'activities.user',
        ])->find($id);

        if (!$aspirasi) {
            throw new \Exception('Aspirasi tidak ditemukan.', 404);
        }

        // Dewan hanya boleh lihat miliknya
        if ($user->isDewan() && $aspirasi->user_id !== $user->id) {
            throw new \Exception('Anda tidak memiliki akses ke aspirasi ini.', 403);
        }

        return $aspirasi;
    }

    /**
     * Update aspirasi.
     */
    public function update(User $user, int $id, array $data): Aspirasi
    {
        $aspirasi = $this->show($user, $id);

        if ($aspirasi->is_used_in_pokir) {
            throw new \Exception('Aspirasi yang sudah digunakan dalam Pokir tidak dapat diubah.', 422);
        }

        $old = $aspirasi->only(array_keys($data));
        $aspirasi->update($data);

        $this->logActivity($aspirasi, $user, 'updated', [
            'old' => $old,
            'new' => $aspirasi->fresh()->only(array_keys($data)),
        ]);

        return $aspirasi->load(['user', 'desa', 'kecamatan', 'dapil', 'opd']);
    }

    /**
     * Hapus aspirasi (soft delete).
     */
    public function destroy(User $user, int $id): void
    {
        $aspirasi = $this->show($user, $id);

        if ($aspirasi->is_used_in_pokir) {
            throw new \Exception('Aspirasi yang sudah digunakan dalam Pokir tidak dapat dihapus.', 422);
        }

        $this->logActivity($aspirasi, $user, 'deleted');
        $aspirasi->delete();
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    private function generateCode(): string
    {
        $year   = date('Y');
        $latest = Aspirasi::withTrashed()
            ->whereYear('created_at', $year)
            ->count();

        return sprintf('ASP-%s-%05d', $year, $latest + 1);
    }

    private function logActivity(Aspirasi $aspirasi, User $user, string $action, ?array $changes = null): void
    {
        AspirasiActivity::create([
            'aspirasi_id' => $aspirasi->id,
            'user_id'     => $user->id,
            'action'      => $action,
            'changes'     => $changes,
            'created_at'  => now(),
        ]);
    }
}
