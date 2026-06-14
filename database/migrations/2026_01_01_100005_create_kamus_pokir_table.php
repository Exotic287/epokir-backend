<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamus_pokirs', function (Blueprint $table) {
            $table->id();
            $table->string('kamus_version');
            $table->tinyInteger('level'); // 1, 2, atau 3
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreignId('opd_id')->nullable()->constrained('opds')->nullOnDelete();
            $table->foreignId('program_sipd_id')->nullable()->constrained('program_sipds')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('kamus_pokirs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamus_pokirs');
    }
};
