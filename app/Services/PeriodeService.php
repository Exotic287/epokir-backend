<?php

namespace App\Services;

use App\Models\Periode;
use Illuminate\Database\Eloquent\Collection;

class PeriodeService
{
    public function getAll(): Collection
    {
        return Periode::orderByDesc('created_at')->get();
    }

    public function getActive(): ?Periode
    {
        return Periode::whereIn('status', ['active', 'frozen'])->first();
    }

    public function find(int $id): Periode
    {
        $periode = Periode::find($id);
        if (!$periode) {
            throw new \Exception('Periode tidak ditemukan.', 404);
        }
        return $periode;
    }

    public function create(array $data, ?string $createdBy): Periode
    {
        if ($this->getActive()) {
            throw new \Exception('Sudah ada periode yang sedang berjalan. Nonaktifkan periode tersebut sebelum membuat periode baru.', 422);
        }

        return Periode::create([
            ...$data,
            'status' => 'active',
            'created_by' => $createdBy,
        ]);
    }

    public function update(int $id, array $data): Periode
    {
        $periode = $this->find($id);
        $periode->update($data);
        return $periode;
    }

    public function freeze(int $id): Periode
    {
        $periode = $this->find($id);
        if ($periode->status !== 'active') {
            throw new \Exception('Hanya periode aktif yang bisa di-freeze.', 422);
        }
        $periode->update(['status' => 'frozen']);
        return $periode;
    }

    public function activate(int $id): Periode
    {
        $periode = $this->find($id);
        if ($periode->status !== 'frozen') {
            throw new \Exception('Hanya periode yang sedang dibekukan yang bisa diaktifkan kembali.', 422);
        }
        $periode->update(['status' => 'active']);
        return $periode;
    }

    public function deactivate(int $id, ?string $alasan = null): Periode
    {
        $periode = $this->find($id);
        if ($periode->status === 'inactive') {
            throw new \Exception('Periode sudah tidak aktif.', 422);
        }
        $periode->update(['status' => 'inactive', 'deactivated_reason' => $alasan]);
        return $periode;
    }
}
