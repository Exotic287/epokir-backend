<?php

namespace App\Services;

use App\Models\Aspirasi;
use App\Models\AspirasiActivity;
use App\Models\KamusUsulan;
use App\Models\Kecamatan;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AspirasiService
{
    /**
     * Daftar aspirasi dengan filter dan paginasi.
     */
    public function index(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Aspirasi::with(['user', 'desa', 'kecamatan', 'dapil', 'opd', 'kamusUsulan.bidangUrusan'])
            ->where('is_archived', false)
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
        if (isset($filters['is_used_in_pokir'])) {
            $query->where('is_used_in_pokir', (bool) $filters['is_used_in_pokir']);
        }
        // Filter gabungan dari UI (AspirasiFilterStatus v2.0) — tidak ada kolom is_quick_capture
        // di backend, jadi "perlu_dilengkapi" disederhanakan jadi murni is_complete=false
        if (!empty($filters['status'])) {
            match ($filters['status']) {
                'belum_dipakai' => $query->where('is_used_in_pokir', false),
                'sudah_dipakai' => $query->where('is_used_in_pokir', true),
                'perlu_dilengkapi' => $query->where('is_complete', false),
                'lengkap' => $query->where('is_complete', true),
                default => null,
            };
        }
        if (!empty($filters['kecamatan'])) {
            $query->whereHas('kecamatan', fn ($q) => $q->where('name', $filters['kecamatan']));
        }
        if (!empty($filters['desa'])) {
            $query->whereHas('desa', fn ($q) => $q->where('name', $filters['desa']));
        }
        if (!empty($filters['urusan'])) {
            $query->whereHas('kamusUsulan.bidangUrusan', fn ($q) => $q->where('nama', $filters['urusan']));
        }
        if (!empty($filters['created_by'])) {
            $query->whereHas('user', fn ($q) => $q->where('name', $filters['created_by']));
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
     * Aspirasi yang sudah lengkap dan belum dipakai di Pokir manapun
     * (ditampilkan di picker saat Dewan menyusun Pokir baru).
     */
    public function available(User $user, array $filters = []): array
    {
        $query = Aspirasi::with(['kecamatan', 'desa', 'dapil', 'kamusUsulan.bidangUrusan'])
            ->where('is_archived', false)
            ->where('is_complete', true);

        // Dewan hanya bisa pilih aspirasi miliknya sendiri
        if ($user->isDewan()) {
            $query->where('user_id', $user->id);
        }

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['urusan'])) {
            $query->whereHas('kamusUsulan.bidangUrusan', fn ($q) => $q->where('nama', $filters['urusan']));
        }

        return $query->latest()->get()->map(fn ($a) => [
            'id'        => $a->id,
            'judul'     => $a->title,
            'deskripsi' => $a->description,
            'urusan'    => $a->kamusUsulan?->bidangUrusan?->nama ?? '',
            'urusan_id' => $a->kamusUsulan?->bidang_urusan_id ?? null,
            'dapil'     => $a->dapil?->name ?? '',
            'sumber'    => $a->source ?? '',
            'kecamatan' => $a->kecamatan?->name ?? '',
            'desa'      => $a->desa?->name ?? '',
        ])->all();
    }

    /**
     * Jumlah aspirasi per tab sumber — scoping sama dengan index() (Dewan hanya
     * miliknya sendiri, arsip dikecualikan) supaya angkanya selalu konsisten dengan daftarnya.
     */
    public function tabCounts(User $user, array $filters = []): array
    {
        $query = Aspirasi::where('is_archived', false);

        if ($user->isDewan()) {
            $query->where('user_id', $user->id);
        }

        if (!empty($filters['created_by'])) {
            $query->whereHas('user', fn ($q) => $q->where('name', $filters['created_by']));
        }

        if (!empty($filters['tahun'])) {
            $query->whereYear('tanggal', (int) $filters['tahun']);
        }

        $perSource = (clone $query)
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        return [
            'semua' => (clone $query)->count(),
            'rdp_audiensi' => $perSource->get('rdp_audiensi', 0),
            'kunjungan_kerja' => $perSource->get('kunjungan_kerja', 0),
            'rapat_mitra' => $perSource->get('rapat_mitra', 0),
            'musrenbang' => $perSource->get('musrenbang', 0),
            'e_reses' => $perSource->get('e_reses', 0),
        ];
    }

    /**
     * Buat aspirasi baru (termasuk draft progresif dari form workspace).
     */
    public function store(User $user, array $data): Aspirasi
    {
        $data['user_id'] = $user->id;
        $data['code']    = $this->generateCode();

        $this->applyDerivedFields($data);
        $data['is_complete'] = $this->computeIsComplete($data);

        $aspirasi = Aspirasi::create($data);

        $this->logActivity($aspirasi, $user, 'created');

        return $aspirasi->load(['user', 'desa', 'kecamatan', 'dapil', 'opd', 'kamusUsulan.bidangUrusan']);
    }

    /**
     * Detail aspirasi.
     */
    public function show(User $user, int $id): Aspirasi
    {
        $aspirasi = Aspirasi::with([
            'user', 'desa', 'kecamatan', 'dapil', 'opd', 'kamusUsulan.bidangUrusan',
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
     * Update aspirasi (termasuk menyimpan ulang draft yang sama / mengajukan).
     */
    public function update(User $user, int $id, array $data): Aspirasi
    {
        $aspirasi = $this->show($user, $id);

        if ($aspirasi->is_used_in_pokir) {
            throw new \Exception('Aspirasi yang sudah digunakan dalam Pokir tidak dapat diubah.', 422);
        }

        $old = $aspirasi->only(array_keys($data));

        $this->applyDerivedFields($data, $aspirasi);
        // Kelengkapan dihitung dari gabungan data lama + perubahan baru, bukan hanya payload yang dikirim
        $data['is_complete'] = $this->computeIsComplete([...$aspirasi->toArray(), ...$data]);

        $aspirasi->update($data);

        $this->logActivity($aspirasi, $user, 'updated', [
            'old' => $old,
            'new' => $aspirasi->fresh()->only(array_keys($data)),
        ]);

        return $aspirasi->load(['user', 'desa', 'kecamatan', 'dapil', 'opd', 'kamusUsulan.bidangUrusan']);
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

    /**
     * Pindahkan sejumlah aspirasi ke arsip kerja — disembunyikan dari daftar aktif (index()),
     * tapi datanya tetap ada (bukan delete).
     */
    public function bulkArchive(User $user, array $ids): int
    {
        $count = 0;
        foreach ($ids as $id) {
            $aspirasi = $this->show($user, (int) $id);
            $aspirasi->update(['is_archived' => true]);
            $this->logActivity($aspirasi, $user, 'archived');
            $count++;
        }

        return $count;
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    /**
     * Auto-assign opd_id (dari Kamus Usulan) dan dapil_id (dari Kecamatan) —
     * keduanya tidak boleh dipilih manual oleh Dewan, mengikuti referensi Kamus.
     */
    private function applyDerivedFields(array &$data, ?Aspirasi $existing = null): void
    {
        $kamusUsulanId = $data['kamus_usulan_id'] ?? $existing?->kamus_usulan_id;
        if ($kamusUsulanId) {
            $kamus = KamusUsulan::find($kamusUsulanId);
            if ($kamus) {
                $opd = Opd::where('name', $kamus->opd_tujuan)->first();
                $data['opd_id'] = $opd?->id;
            }
        }

        $kecamatanId = $data['kecamatan_id'] ?? $existing?->kecamatan_id;
        if ($kecamatanId) {
            $kecamatan = Kecamatan::find($kecamatanId);
            $data['dapil_id'] = $kecamatan?->dapil_id;
        }
    }

    /**
     * Replikasi aturan blocker di useAspirasiForm.ts (frontend) — kelengkapan dihitung
     * di server, tidak dipercayakan ke flag dari client.
     */
    private function computeIsComplete(array $data): bool
    {
        if (empty($data['title']) || empty($data['description']) || mb_strlen((string) $data['description']) < 20) {
            return false;
        }
        if (empty($data['kamus_usulan_id']) || empty($data['kecamatan_id']) || empty($data['desa_id'])) {
            return false;
        }

        $skemaLokasi = KamusUsulan::find($data['kamus_usulan_id'])?->skema_lokasi;
        if ($skemaLokasi === 'A' && empty($data['alamat'])) {
            return false;
        }

        return true;
    }

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
