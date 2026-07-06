<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamus_pokirs', function (Blueprint $table) {
            $table->string('name')->after('kamus_version');
            $table->json('supporting_opd_ids')->nullable()->after('opd_id');
        });
    }

    public function down(): void
    {
        Schema::table('kamus_pokirs', function (Blueprint $table) {
            $table->dropColumn(['name', 'supporting_opd_ids']);
        });
    }
};
