<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamus_pokirs', function (Blueprint $table) {
            $table->tinyInteger('level')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('kamus_pokirs', function (Blueprint $table) {
            $table->tinyInteger('level')->nullable(false)->change();
        });
    }
};
