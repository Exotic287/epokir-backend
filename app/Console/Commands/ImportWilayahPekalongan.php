<?php

namespace App\Console\Commands;

use App\Models\Dapil;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ImportWilayahPekalongan extends Command
{
    protected $signature = 'wilayah:import-pekalongan {--regency=3326 : Kode BPS kabupaten/kota (default Kabupaten Pekalongan)}';

    protected $description = 'Import Dapil, Kecamatan, dan Desa Kabupaten Pekalongan dari api-wilayah-indonesia (emsifa)';

    private const BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    /**
     * Pemetaan Dapil DPRD Kab. Pekalongan → nama kecamatan (sesuai daerah pemilihan,
     * bukan bagian dari data wilayah administratif BPS — referensi: Dapil & Fraksi SSO Pusat).
     */
    private const DAPIL_MAP = [
        1 => ['jumlah_kursi' => 7, 'kecamatan' => ['KAJEN', 'KANDANGSERANG', 'PANINGGARAN']],
        2 => ['jumlah_kursi' => 10, 'kecamatan' => ['KESESI', 'SRAGI', 'BOJONG']],
        3 => ['jumlah_kursi' => 10, 'kecamatan' => ['SIWALAN', 'WIRADESA', 'TIRTO', 'WONOKERTO']],
        4 => ['jumlah_kursi' => 11, 'kecamatan' => ['WONOPRINGGO', 'BUARAN', 'KEDUNGWUNI', 'KARANGDADAP']],
        5 => ['jumlah_kursi' => 7, 'kecamatan' => ['PETUNGKRIONO', 'TALUN', 'DORO', 'KARANGANYAR', 'LEBAKBARANG']],
    ];

    public function handle(): int
    {
        $regencyId = (string) $this->option('regency');

        // 1. Dapil — idempoten via number
        $dapilIdByNumber = [];
        foreach (self::DAPIL_MAP as $number => $config) {
            // Nama HARUS sama persis dengan "nama" Dapil di SSO Pusat (tabel dapils di sso-setwan) —
            // AuthService::handleSsoCallback() mencocokkan berdasarkan nama ini saat sinkronisasi login.
            $dapil = Dapil::updateOrCreate(
                ['number' => $number],
                ['name' => "Dapil Pekalongan {$number}", 'description' => "{$config['jumlah_kursi']} kursi", 'is_active' => true],
            );
            $dapilIdByNumber[$number] = $dapil->id;
        }
        $this->info('Dapil tersimpan: ' . count($dapilIdByNumber));

        // Nama kecamatan → nomor dapil, untuk lookup cepat saat import
        $dapilByKecamatanName = [];
        foreach (self::DAPIL_MAP as $number => $config) {
            foreach ($config['kecamatan'] as $nama) {
                $dapilByKecamatanName[$nama] = $number;
            }
        }

        // 2. Kecamatan dari API
        $districts = Http::timeout(30)->get(self::BASE_URL . "/districts/{$regencyId}.json");
        if ($districts->failed()) {
            $this->error('Gagal mengambil data kecamatan dari api-wilayah-indonesia: ' . $districts->status());
            return self::FAILURE;
        }

        $kecamatanCount = 0;
        $desaCount = 0;
        $unmapped = [];

        foreach ($districts->json() as $district) {
            $dapilNumber = $dapilByKecamatanName[$district['name']] ?? null;
            if (!$dapilNumber) {
                // dapil_id NOT NULL di tabel kecamatans/desas — kecamatan tanpa pemetaan Dapil
                // dilewati saja (bukan default ke 0/null) supaya tidak melanggar constraint.
                $unmapped[] = $district['name'];
                continue;
            }

            $kecamatan = Kecamatan::updateOrCreate(
                ['code' => $district['id']],
                [
                    'name' => $district['name'],
                    'dapil_id' => $dapilIdByNumber[$dapilNumber],
                    'is_active' => true,
                ],
            );
            $kecamatanCount++;

            // 3. Desa per kecamatan dari API
            $villages = Http::timeout(30)->get(self::BASE_URL . "/villages/{$district['id']}.json");
            if ($villages->failed()) {
                $this->warn("  Gagal mengambil desa untuk {$district['name']}: " . $villages->status());
                continue;
            }

            foreach ($villages->json() as $village) {
                Desa::updateOrCreate(
                    ['code' => $village['id']],
                    [
                        'name' => $village['name'],
                        'kecamatan_id' => $kecamatan->id,
                        'dapil_id' => $kecamatan->dapil_id,
                    ],
                );
                $desaCount++;
            }

            $this->line("  {$district['name']}: " . count($villages->json()) . ' desa');
        }

        // MasterDataService meng-cache hasil query (3600s) dan biasanya membersihkannya
        // sendiri lewat createKecamatan()/createDesa() — command ini menulis langsung via
        // Eloquent, jadi cache harus dibersihkan manual di sini.
        Cache::flush();

        $this->info("Selesai. Kecamatan: {$kecamatanCount}, Desa: {$desaCount}.");

        if ($unmapped) {
            $this->warn('Kecamatan tanpa pemetaan Dapil (dapil_id null): ' . implode(', ', $unmapped));
        }

        return self::SUCCESS;
    }
}
