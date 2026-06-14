<?php

namespace App\Services;

use App\Models\Aspirasi;
use App\Models\Pokir;
use App\Models\PokirActivity;
use App\Models\PokirRevision;
use App\Models\PokirRevisionFlagged;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PokirService
{
    /**
     * Daftar Pokir dengan filter dan paginasi.
     */
    public function index(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Pokir::with(['user', 'kamusPokir', 'opd', 'dapil'])->latest();

        // Dewan hanya lihat pokir miliknya
        if ($user->isDewan()) {
            $query->where('user_id', $user->id);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['opd_id'])) {
            $query->where('opd_id', $filters['opd_id']);
        }
        if (!empty($filters['dapil_id'])) {
            $query->where('dapil_id', $filters['dapil_id']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Buat Pokir baru (status: draft).
     */
    public function store(User $user, array $data): Pokir
    {
        if (!$user->isDewan() && !$user->isAdmin()) {
            throw new \Exception('Hanya anggota dewan yang dapat membuat Pokir.', 403);
        }

        $data['user_id'] = $user->id;
        $data['status']  = 'draft';
        $data['number']  = $this->generateNumber();

        $pokir = Pokir::create($data);

        $this->logActivity($pokir, $user, 'created');

        return $pokir->load(['user', 'kamusPokir', 'opd', 'dapil']);
    }

    /**
     * Detail Pokir.
     */
    public function show(User $user, int $id): Pokir
    {
        $pokir = Pokir::with([
            'user', 'kamusPokir.opd', 'opd', 'dapil',
            'submittedBy', 'verifiedBy', 'finalizedBy',
            'aspirasi.desa', 'aspirasi.kecamatan',
            'activities.user',
            'revisionsFlagged.flaggedBy',
            'attachments',
        ])->find($id);

        if (!$pokir) {
            throw new \Exception('Pokir tidak ditemukan.', 404);
        }

        if ($user->isDewan() && $pokir->user_id !== $user->id) {
            throw new \Exception('Anda tidak memiliki akses ke Pokir ini.', 403);
        }

        return $pokir;
    }

    /**
     * Update Pokir (hanya jika masih draft atau revision_needed).
     */
    public function update(User $user, int $id, array $data): Pokir
    {
        $pokir = $this->show($user, $id);

        if (!in_array($pokir->status, ['draft', 'revision_needed'])) {
            throw new \Exception('Pokir tidak dapat diubah pada status saat ini.', 422);
        }

        $trackableFields = ['title', 'kamus_pokir_id', 'opd_id', 'dapil_id', 'kecamatan_ids', 'desa_ids', 'notes'];
        $changes         = [];

        foreach ($trackableFields as $field) {
            if (array_key_exists($field, $data) && $pokir->$field != $data[$field]) {
                $changes[$field] = ['old' => $pokir->$field, 'new' => $data[$field]];
            }
        }

        $pokir->update($data);

        if (!empty($changes)) {
            $activity = $this->logActivity($pokir, $user, 'updated', $changes);
            $this->logRevisions($pokir, $activity, $user, $changes);
        }

        return $pokir->load(['user', 'kamusPokir', 'opd', 'dapil']);
    }

    /**
     * Hapus Pokir (soft delete, hanya jika draft).
     */
    public function destroy(User $user, int $id): void
    {
        $pokir = $this->show($user, $id);

        if ($pokir->status !== 'draft') {
            throw new \Exception('Hanya Pokir berstatus draft yang dapat dihapus.', 422);
        }

        $this->logActivity($pokir, $user, 'created'); // log deletion
        $pokir->delete();
    }

    /**
     * Submit Pokir (draft → submitted).
     */
    public function submit(User $user, int $id): Pokir
    {
        $pokir = $this->show($user, $id);

        if (!in_array($pokir->status, ['draft', 'revision_needed'])) {
            throw new \Exception('Pokir tidak dapat disubmit pada status saat ini.', 422);
        }

        $pokir->update([
            'status'       => 'submitted',
            'submitted_by' => $user->id,
        ]);

        $this->logActivity($pokir, $user, 'submitted');

        return $pokir->load(['user', 'opd', 'dapil']);
    }

    /**
     * Verifikasi Pokir (submitted → verified). Hanya setwan/admin.
     */
    public function verify(User $user, int $id): Pokir
    {
        if (!$user->isSetwan() && !$user->isAdmin()) {
            throw new \Exception('Hanya Setwan yang dapat memverifikasi Pokir.', 403);
        }

        $pokir = Pokir::find($id);
        if (!$pokir) {
            throw new \Exception('Pokir tidak ditemukan.', 404);
        }

        if ($pokir->status !== 'submitted') {
            throw new \Exception('Pokir harus berstatus submitted untuk diverifikasi.', 422);
        }

        $pokir->update([
            'status'      => 'verified',
            'verified_by' => $user->id,
        ]);

        $this->logActivity($pokir, $user, 'verified');

        return $pokir->load(['user', 'opd', 'dapil']);
    }

    /**
     * Minta revisi Pokir (submitted → revision_needed). Hanya setwan/admin.
     */
    public function requestRevision(User $user, int $id, array $flags): Pokir
    {
        if (!$user->isSetwan() && !$user->isAdmin()) {
            throw new \Exception('Hanya Setwan yang dapat meminta revisi Pokir.', 403);
        }

        $pokir = Pokir::find($id);
        if (!$pokir) {
            throw new \Exception('Pokir tidak ditemukan.', 404);
        }

        if ($pokir->status !== 'submitted') {
            throw new \Exception('Pokir harus berstatus submitted untuk diminta revisi.', 422);
        }

        DB::transaction(function () use ($pokir, $user, $flags) {
            $pokir->update(['status' => 'revision_needed']);

            foreach ($flags as $flag) {
                PokirRevisionFlagged::create([
                    'pokir_id'   => $pokir->id,
                    'field_name' => $flag['field_name'],
                    'note'       => $flag['note'] ?? null,
                    'is_resolved'=> false,
                    'flagged_by' => $user->id,
                ]);
            }

            $this->logActivity($pokir, $user, 'revision_requested', ['flags' => $flags]);
        });

        return $pokir->load(['user', 'opd', 'dapil', 'revisionsFlagged']);
    }

    /**
     * Finalisasi Pokir (verified → finalized). Hanya admin.
     */
    public function finalize(User $user, int $id): Pokir
    {
        if (!$user->isAdmin()) {
            throw new \Exception('Hanya Admin yang dapat memfinalisasi Pokir.', 403);
        }

        $pokir = Pokir::find($id);
        if (!$pokir) {
            throw new \Exception('Pokir tidak ditemukan.', 404);
        }

        if ($pokir->status !== 'verified') {
            throw new \Exception('Pokir harus berstatus verified untuk difinalisasi.', 422);
        }

        $pokir->update([
            'status'       => 'finalized',
            'finalized_by' => $user->id,
        ]);

        $this->logActivity($pokir, $user, 'finalized');

        return $pokir->load(['user', 'opd', 'dapil']);
    }

    /**
     * Tambah aspirasi ke Pokir.
     */
    public function addAspirasi(User $user, int $pokirId, int $aspirasiId, int $position = 0): Pokir
    {
        $pokir    = $this->show($user, $pokirId);
        $aspirasi = Aspirasi::find($aspirasiId);

        if (!$aspirasi) {
            throw new \Exception('Aspirasi tidak ditemukan.', 404);
        }

        if (!in_array($pokir->status, ['draft', 'revision_needed'])) {
            throw new \Exception('Tidak dapat menambah aspirasi pada status Pokir saat ini.', 422);
        }

        if ($pokir->aspirasi()->where('aspirasi_id', $aspirasiId)->exists()) {
            throw new \Exception('Aspirasi sudah ada dalam Pokir ini.', 422);
        }

        $pokir->aspirasi()->attach($aspirasiId, [
            'position' => $position,
            'added_at' => now(),
        ]);

        $aspirasi->update(['is_used_in_pokir' => true]);

        $this->logActivity($pokir, $user, 'updated', ['added_aspirasi_id' => $aspirasiId]);

        return $pokir->load(['aspirasi']);
    }

    /**
     * Lepas aspirasi dari Pokir.
     */
    public function removeAspirasi(User $user, int $pokirId, int $aspirasiId): Pokir
    {
        $pokir = $this->show($user, $pokirId);

        if (!in_array($pokir->status, ['draft', 'revision_needed'])) {
            throw new \Exception('Tidak dapat melepas aspirasi pada status Pokir saat ini.', 422);
        }

        $pokir->aspirasi()->detach($aspirasiId);

        // Cek apakah aspirasi masih dipakai di pokir lain
        $stillUsed = Aspirasi::find($aspirasiId)
            ?->pokir()->withTrashed()->exists() ?? false;

        if (!$stillUsed) {
            Aspirasi::find($aspirasiId)?->update(['is_used_in_pokir' => false]);
        }

        $this->logActivity($pokir, $user, 'updated', ['removed_aspirasi_id' => $aspirasiId]);

        return $pokir->load(['aspirasi']);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    private function generateNumber(): string
    {
        $year   = date('Y');
        $latest = Pokir::withTrashed()->whereYear('created_at', $year)->count();
        return sprintf('PKR-%s-%05d', $year, $latest + 1);
    }

    private function logActivity(Pokir $pokir, User $user, string $action, ?array $changes = null): PokirActivity
    {
        return PokirActivity::create([
            'pokir_id'   => $pokir->id,
            'user_id'    => $user->id,
            'action'     => $action,
            'changes'    => $changes,
            'created_at' => now(),
        ]);
    }

    private function logRevisions(Pokir $pokir, PokirActivity $activity, User $user, array $changes): void
    {
        foreach ($changes as $field => $change) {
            PokirRevision::create([
                'pokir_id'          => $pokir->id,
                'pokir_activity_id' => $activity->id,
                'field_name'        => $field,
                'old_value'         => is_array($change['old']) ? json_encode($change['old']) : $change['old'],
                'new_value'         => is_array($change['new']) ? json_encode($change['new']) : $change['new'],
                'changed_by'        => $user->id,
                'created_at'        => now(),
            ]);
        }
    }
}
