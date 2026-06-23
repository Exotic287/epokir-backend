<?php

namespace App\Services;

use App\Models\BidangUrusan;
use App\Models\KamusUsulan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class KamusUsulanService
{
    public function getGrouped(array $filters = []): array
    {
        $items = $this->queryItems($filters)->get();

        $groups = $items->groupBy('bidang_urusan_id')->map(function (Collection $groupItems) {
            $bidang = $groupItems->first()->bidangUrusan;
            return [
                'bidang_urusan' => $bidang->nama,
                'bidang_urusan_id' => $bidang->id,
                'items' => $groupItems->map(fn (KamusUsulan $i) => $this->formatItem($i))->values()->all(),
            ];
        })->values()->all();

        usort($groups, fn ($a, $b) => strcmp($a['bidang_urusan'], $b['bidang_urusan']));

        $allItems = KamusUsulan::all();

        return [
            'data' => $groups,
            'meta' => [
                'total_bidang' => BidangUrusan::has('kamusUsulan')->count(),
                'total_item' => $allItems->count(),
                'total_active' => $allItems->where('status', 'active')->count(),
                'total_inactive' => $allItems->where('status', 'inactive')->count(),
            ],
        ];
    }

    public function getFlat(array $filters = []): array
    {
        $items = $this->queryItems($filters)->orderByDesc('created_at')->get();

        return [
            'data' => $items->map(fn (KamusUsulan $i) => $this->formatItem($i))->values()->all(),
            'meta' => [
                'total' => $items->count(),
                'page' => 1,
                'per_page' => $items->count(),
            ],
        ];
    }

    public function getBidangUrusanList(): array
    {
        return BidangUrusan::withCount('kamusUsulan as jumlah_item')
            ->orderBy('nama')
            ->get()
            ->map(fn (BidangUrusan $b) => [
                'id' => $b->id,
                'nama' => $b->nama,
                'jumlah_item' => $b->jumlah_item,
            ])->all();
    }

    public function find(int $id): KamusUsulan
    {
        $item = KamusUsulan::with('bidangUrusan')->find($id);
        if (!$item) {
            throw new \Exception('Item kamus tidak ditemukan.', 404);
        }
        return $item;
    }

    public function create(array $data): array
    {
        $data['bidang_urusan_id'] = $this->resolveBidangUrusanId($data);
        $item = KamusUsulan::create($data);
        return $this->formatItem($item->load('bidangUrusan'));
    }

    public function update(int $id, array $data): array
    {
        $item = $this->find($id);
        if (array_key_exists('bidang_urusan_id', $data)) {
            $data['bidang_urusan_id'] = $this->resolveBidangUrusanId($data);
        }
        $item->update($data);
        return $this->formatItem($item->load('bidangUrusan'));
    }

    public function toggleStatus(int $id): array
    {
        $item = $this->find($id);
        $item->update(['status' => $item->status === 'active' ? 'inactive' : 'active']);
        return $this->formatItem($item);
    }

    private function queryItems(array $filters): Builder
    {
        $query = KamusUsulan::with('bidangUrusan');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function (Builder $sub) use ($q) {
                $sub->where('uraian_permasalahan', 'like', "%{$q}%")
                    ->orWhereHas('bidangUrusan', fn (Builder $b) => $b->where('nama', 'like', "%{$q}%"));
            });
        }
        if (!empty($filters['skema']) && $filters['skema'] !== 'all') {
            $query->where('skema_lokasi', $filters['skema']);
        }
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        return $query;
    }

    private function resolveBidangUrusanId(array &$data): int
    {
        $bidangId = (int) ($data['bidang_urusan_id'] ?? 0);

        if ($bidangId > 0) {
            if (!BidangUrusan::where('id', $bidangId)->exists()) {
                throw new \Exception('Bidang urusan tidak ditemukan.', 404);
            }
            unset($data['bidang_urusan_baru']);
            return $bidangId;
        }

        $nama = trim($data['bidang_urusan_baru'] ?? '');
        if ($nama === '') {
            throw new \Exception('Nama bidang urusan baru wajib diisi.', 422);
        }
        $bidang = BidangUrusan::firstOrCreate(['nama' => $nama]);
        unset($data['bidang_urusan_baru']);
        return $bidang->id;
    }

    private function formatItem(KamusUsulan $item): array
    {
        return [
            'id' => $item->id,
            'bidang_urusan_id' => $item->bidang_urusan_id,
            'bidang_urusan' => $item->bidangUrusan->nama,
            'uraian_permasalahan' => $item->uraian_permasalahan,
            'opd_tujuan' => $item->opd_tujuan,
            'program' => $item->program,
            'skema_lokasi' => $item->skema_lokasi,
            'status' => $item->status,
            'created_at' => $item->created_at->toIso8601String(),
            'updated_at' => $item->updated_at->toIso8601String(),
        ];
    }
}
