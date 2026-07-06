<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pokirs', function (Blueprint $table) {
            // Drop old FK dan kolom kamus_pokir_id, lalu tambah kamus_usulan_id
            $table->dropForeign(['kamus_pokir_id']);
            $table->dropColumn('kamus_pokir_id');
            $table->foreignId('kamus_usulan_id')->nullable()->after('title')->constrained('kamus_usulans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pokirs', function (Blueprint $table) {
            $table->dropForeign(['kamus_usulan_id']);
            $table->dropColumn('kamus_usulan_id');
            $table->foreignId('kamus_pokir_id')->nullable()->after('title')->constrained('kamus_pokirs')->nullOnDelete();
        });
    }
};
