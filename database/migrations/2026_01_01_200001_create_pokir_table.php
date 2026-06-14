<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('number')->unique();
            $table->string('title');
            $table->foreignId('kamus_pokir_id')->nullable()->constrained('kamus_pokirs')->nullOnDelete();
            $table->foreignId('opd_id')->nullable()->constrained('opds')->nullOnDelete();
            $table->foreignId('dapil_id')->nullable()->constrained('dapils')->nullOnDelete();
            $table->enum('status', [
                'draft',
                'submitted',
                'revision_needed',
                'verified',
                'finalized',
                'exported',
                'cancelled',
            ])->default('draft');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('kecamatan_ids')->nullable();
            $table->json('desa_ids')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokirs');
    }
};
