<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamus_usulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_urusan_id')->constrained('bidang_urusans');
            $table->string('uraian_permasalahan');
            $table->string('opd_tujuan');
            $table->string('program', 300);
            $table->string('skema_lokasi', 1);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamus_usulans');
    }
};
