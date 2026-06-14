<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokir_aspirasis', function (Blueprint $table) {
            $table->foreignId('pokir_id')->constrained('pokirs')->cascadeOnDelete();
            $table->foreignId('aspirasi_id')->constrained('aspirasis')->cascadeOnDelete();
            $table->integer('position')->default(0);
            $table->timestamp('added_at')->useCurrent();

            $table->primary(['pokir_id', 'aspirasi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokir_aspirasis');
    }
};
