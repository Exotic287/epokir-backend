<?php
namespace App\Services;

use App\Models\KamusPokir;

class KamusPokirService
{
    /**
     * Semua KamusPokir untuk halaman admin.
     */
    public function getAll(): array
    {
        return KamusPokir::with(['bidangUrusan', 'opd'])
            ->orderBy('bidang_urusan_id')
            ->orderBy('name')
            ->get()
            ->map(fn(KamusPokir $k) => $this->format($k))
            ->all();
    }

    /**
     * Flat list aktif untuk picker Pokir (Dewan).
     */
    public function getCategories(): array
    {
        return KamusPokir::with(['bidangUrusan', 'opd'])
            ->where('is_active', true)
            ->orderBy('bidang_urusan_id')
            ->orderBy('name')
            ->get()
            ->map(fn(KamusPokir $k) => [
                'id'             => (string) $k->id,
                'name'           => $k->name,
                'bidang_id'      => $k->bidang_urusan_id ? (string) $k->bidang_urusan_id : null,
                'bidang_name'    => $k->bidangUrusan?->nama ?? '',
                'opd_id'         => $k->opd_id ? (string) $k->opd_id : null,
                'opd_name'       => $k->opd?->name ?? '',
                'program_sipd'   => '',
                'supporting_opds'=> [],
            ])->all();
    }

    public function find(int $id): KamusPokir
    {
        $item = KamusPokir::with(['bidangUrusan', 'opd'])->find($id);
        if (!$item) throw new \Exception('Kamus Pokir tidak ditemukan', 404);
        return $item;
    }

    public function create(array $data): array
    {
        $item = KamusPokir::create($data);
        return $this->format($item->load(['bidangUrusan', 'opd']));
    }

    public function update(int $id, array $data): array
    {
        $item = $this->find($id);
        $item->update($data);
        return $this->format($item->load(['bidangUrusan', 'opd']));
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }

    public function toggleActive(int $id): array
    {
        $item = $this->find($id);
        $item->update(['is_active' => !$item->is_active]);
        return $this->format($item);
    }

    private function format(KamusPokir $k): array
    {
        return [
            'id'               => (string) $k->id,
            'kamus_version'    => $k->kamus_version,
            'name'             => $k->name,
            'bidang_urusan_id' => $k->bidang_urusan_id ? (string) $k->bidang_urusan_id : null,
            'bidang_name'      => $k->bidangUrusan?->nama,
            'opd_id'           => $k->opd_id ? (string) $k->opd_id : null,
            'opd_name'         => $k->opd?->name,
            'is_active'        => $k->is_active,
            'created_at'       => $k->created_at?->toISOString(),
        ];
    }
}
