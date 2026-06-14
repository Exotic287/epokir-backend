<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('title');
            $table->foreignId('desa_id')->nullable()->constrained('desas')->nullOnDelete();
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('dapil_id')->nullable()->constrained('dapils')->nullOnDelete();
            $table->foreignId('opd_id')->nullable()->constrained('opds')->nullOnDelete();
            $table->date('tanggal');
            $table->enum('source', ['reses', 'tatap_muka', 'surat', 'lainnya'])->default('lainnya');
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_used_in_pokir')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
