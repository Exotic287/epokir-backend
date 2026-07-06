<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->foreignId('kamus_usulan_id')->nullable()->after('opd_id')->constrained('kamus_usulans')->nullOnDelete();
            $table->string('alamat')->nullable()->after('source');
            $table->decimal('latitude', 10, 8)->nullable()->after('alamat');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->text('narasi_dokumen')->nullable()->after('description');
        });

        // source: dari enum tetap ke string bebas (selaras dengan AspirasiSumber frontend) —
        // pakai SQL mentah karena doctrine/dbal tidak terpasang untuk Schema::table()->change()
        DB::statement("ALTER TABLE aspirasis MODIFY source VARCHAR(255) NOT NULL DEFAULT 'rdp_audiensi'");

        // Field-field ini wajib diisi penuh hanya saat submit final — saat draft progresif,
        // form bisa disimpan sebelum semuanya terisi
        DB::statement('ALTER TABLE aspirasis MODIFY title VARCHAR(255) NULL');
        DB::statement('ALTER TABLE aspirasis MODIFY tanggal DATE NULL');
    }

    public function down(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kamus_usulan_id');
            $table->dropColumn(['alamat', 'latitude', 'longitude', 'narasi_dokumen']);
        });

        DB::statement("ALTER TABLE aspirasis MODIFY source ENUM('reses', 'tatap_muka', 'surat', 'lainnya') NOT NULL DEFAULT 'lainnya'");
        DB::statement('ALTER TABLE aspirasis MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE aspirasis MODIFY tanggal DATE NOT NULL');
    }
};
