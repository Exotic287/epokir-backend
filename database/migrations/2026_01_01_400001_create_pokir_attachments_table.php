<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokir_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokir_id')->constrained('pokirs')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('type', ['dokumen_pendukung', 'foto', 'laporan', 'lainnya'])->default('lainnya');
            $table->string('file_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokir_attachments');
    }
};
