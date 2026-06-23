<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal_buka');
            $table->date('batas_submit');
            $table->date('jadwal_freeze');
            $table->string('status')->default('active');
            $table->string('created_by')->nullable();
            $table->string('deactivated_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
