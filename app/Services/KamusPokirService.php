<?php
namespace App\Services;

use App\Models\KamusPokir;

class KamusPokirService
{
    public function getAll()
    {
        return KamusPokir::with(['parent', 'opd', 'programSipd'])->get();
    }

    public function find(int $id)
    {
        $data = KamusPokir::with(['parent', 'opd', 'programSipd'])->find($id);
        if (!$data) {
            throw new \Exception('Kamus Pokir tidak ditemukan', 404);
        }
        return $data;
    }

    public function create(array $data)
    {
        return KamusPokir::create($data);
    }

    public function update(int $id, array $data)
    {
        $kamus = $this->find($id);
        $kamus->update($data);
        return $kamus;
    }

    public function delete(int $id)
    {
        $kamus = $this->find($id);
        return $kamus->delete();
    }
}
