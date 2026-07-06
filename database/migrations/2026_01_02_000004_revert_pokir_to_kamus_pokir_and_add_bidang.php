<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kembalikan pokirs ke kamus_pokir_id (bukan kamus_usulan_id)
        Schema::table('pokirs', function (Blueprint $table) {
            $table->dropForeign(['kamus_usulan_id']);
            $table->dropColumn('kamus_usulan_id');
            $table->foreignId('kamus_pokir_id')->nullable()->after('title')->constrained('kamus_pokirs')->nullOnDelete();
        });

        // Tambah bidang_urusan_id ke kamus_pokirs agar flat seperti kamus_usulan
        Schema::table('kamus_pokirs', function (Blueprint $table) {
            $table->foreignId('bidang_urusan_id')->nullable()->after('name')->constrained('bidang_urusans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pokirs', function (Blueprint $table) {
            $table->dropForeign(['kamus_pokir_id']);
            $table->dropColumn('kamus_pokir_id');
            $table->foreignId('kamus_usulan_id')->nullable()->after('title')->constrained('kamus_usulans')->nullOnDelete();
        });

        Schema::table('kamus_pokirs', function (Blueprint $table) {
            $table->dropForeign(['bidang_urusan_id']);
            $table->dropColumn('bidang_urusan_id');
        });
    }
};
