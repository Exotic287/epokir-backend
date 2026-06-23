<?php

namespace App\Services;

use App\Models\Dapil;
use App\Models\Desa;
use App\Models\KamusPokir;
use App\Models\Kecamatan;
use App\Models\Opd;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MasterDataService
{
    // ─── OPD ─────────────────────────────────────────────────────────────────

    public function getAllOpd(bool $activeOnly = true, bool $withCounts = false): Collection
    {
        return Cache::remember("opd.all.active:{$activeOnly}.counts:{$withCounts}", 3600, function () use ($activeOnly, $withCounts) {
            $query = Opd::query();
            if ($withCounts) {
                $query->withCount('kamusPokir');
            }
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            return $query->orderBy('name')->get();
        });
    }

    public function findOpd(int $id): Opd
    {
        $opd = Opd::find($id);
        if (!$opd) {
            throw new \Exception('OPD tidak ditemukan.', 404);
        }
        return $opd;
    }

    // ─── Dapil ───────────────────────────────────────────────────────────────

    public function getAllDapil(bool $activeOnly = true): Collection
    {
        return Cache::remember("dapil.all.active:{$activeOnly}", 3600, function () use ($activeOnly) {
            $query = Dapil::query();
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            return $query->orderBy('number')->get();
        });
    }

    public function findDapil(int $id): Dapil
    {
        $dapil = Dapil::find($id);
        if (!$dapil) {
            throw new \Exception('Dapil tidak ditemukan.', 404);
        }
        return $dapil;
    }

    // ─── Kecamatan ───────────────────────────────────────────────────────────

    public function getAllKecamatan(?int $dapilId = null): Collection
    {
        $cacheKey = "kecamatan.all.dapil:{$dapilId}";
        return Cache::remember($cacheKey, 3600, function () use ($dapilId) {
            $query = Kecamatan::where('is_active', true)->with('dapil');
            if ($dapilId) {
                $query->where('dapil_id', $dapilId);
            }
            return $query->orderBy('name')->get();
        });
    }

    public function findKecamatan(int $id): Kecamatan
    {
        $kecamatan = Kecamatan::find($id);
        if (!$kecamatan) {
            throw new \Exception('Kecamatan tidak ditemukan.', 404);
        }
        return $kecamatan;
    }

    // ─── Desa ─────────────────────────────────────────────────────────────────

    public function getAllDesa(?int $kecamatanId = null, ?int $dapilId = null): Collection
    {
        $cacheKey = "desa.all.kec:{$kecamatanId}.dapil:{$dapilId}";
        return Cache::remember($cacheKey, 3600, function () use ($kecamatanId, $dapilId) {
            $query = Desa::with(['kecamatan', 'dapil']);
            if ($kecamatanId) {
                $query->where('kecamatan_id', $kecamatanId);
            }
            if ($dapilId) {
                $query->where('dapil_id', $dapilId);
            }
            return $query->orderBy('name')->get();
        });
    }

    public function findDesa(int $id): Desa
    {
        $desa = Desa::find($id);
        if (!$desa) {
            throw new \Exception('Desa tidak ditemukan.', 404);
        }
        return $desa;
    }

    // ─── Kamus Pokir ─────────────────────────────────────────────────────────

    public function getAllKamusPokir(?string $version = null, ?int $level = null, ?int $opdId = null): Collection
    {
        $cacheKey = "kamus_pokir.v:{$version}.level:{$level}.opd:{$opdId}";
        return Cache::remember($cacheKey, 3600, function () use ($version, $level, $opdId) {
            $query = KamusPokir::where('is_active', true)
                ->with(['parent', 'opd', 'programSipd']);
            if ($version) {
                $query->where('kamus_version', $version);
            }
            if ($level) {
                $query->where('level', $level);
            }
            if ($opdId) {
                $query->where('opd_id', $opdId);
            }
            return $query->orderBy('level')->get();
        });
    }

    public function findKamusPokir(int $id): KamusPokir
    {
        $kamus = KamusPokir::with(['parent', 'opd', 'programSipd'])->find($id);
        if (!$kamus) {
            throw new \Exception('Kamus Pokir tidak ditemukan.', 404);
        }
        return $kamus;
    }

    // ─── CRUD Write Operations ───────────────────────────────────────────────

    public function createOpd(array $data): Opd
    {
        $opd = Opd::create($data);
        Cache::flush();
        return $opd;
    }

    public function updateOpd(int $id, array $data): Opd
    {
        $opd = $this->findOpd($id);
        $opd->update($data);
        Cache::flush();
        return $opd;
    }

    public function deleteOpd(int $id): void
    {
        $opd = $this->findOpd($id);
        if ($opd->programSipd()->exists() || $opd->kamusPokir()->exists() || $opd->aspirasi()->exists() || $opd->pokir()->exists()) {
            throw new \Exception('OPD tidak dapat dihapus karena memiliki data terkait.', 422);
        }
        $opd->delete();
        Cache::flush();
    }

    public function toggleOpdActive(int $id): Opd
    {
        $opd = $this->findOpd($id);
        $opd->update(['is_active' => !$opd->is_active]);
        Cache::flush();
        return $opd;
    }

    public function createDapil(array $data): Dapil
    {
        $dapil = Dapil::create($data);
        Cache::flush();
        return $dapil;
    }

    public function updateDapil(int $id, array $data): Dapil
    {
        $dapil = $this->findDapil($id);
        $dapil->update($data);
        Cache::flush();
        return $dapil;
    }

    public function deleteDapil(int $id): void
    {
        $dapil = $this->findDapil($id);
        if ($dapil->kecamatan()->exists() || $dapil->desa()->exists() || $dapil->users()->exists() || $dapil->aspirasi()->exists() || $dapil->pokir()->exists()) {
            throw new \Exception('Dapil tidak dapat dihapus karena memiliki data terkait.', 422);
        }
        $dapil->delete();
        Cache::flush();
    }

    public function createKecamatan(array $data): Kecamatan
    {
        $kecamatan = Kecamatan::create($data);
        Cache::flush();
        return $kecamatan;
    }

    public function updateKecamatan(int $id, array $data): Kecamatan
    {
        $kecamatan = $this->findKecamatan($id);
        $kecamatan->update($data);
        Cache::flush();
        return $kecamatan;
    }

    public function deleteKecamatan(int $id): void
    {
        $kecamatan = $this->findKecamatan($id);
        if ($kecamatan->desa()->exists() || $kecamatan->aspirasi()->exists()) {
            throw new \Exception('Kecamatan tidak dapat dihapus karena memiliki data terkait.', 422);
        }
        $kecamatan->delete();
        Cache::flush();
    }

    public function createDesa(array $data): Desa
    {
        $desa = Desa::create($data);
        Cache::flush();
        return $desa;
    }

    public function updateDesa(int $id, array $data): Desa
    {
        $desa = $this->findDesa($id);
        $desa->update($data);
        Cache::flush();
        return $desa;
    }

    public function deleteDesa(int $id): void
    {
        $desa = $this->findDesa($id);
        if ($desa->aspirasi()->exists()) {
            throw new \Exception('Desa tidak dapat dihapus karena memiliki data terkait.', 422);
        }
        $desa->delete();
        Cache::flush();
    }
}
