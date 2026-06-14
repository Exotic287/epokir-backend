<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokir_revision_flaggeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokir_id')->constrained('pokirs')->cascadeOnDelete();
            $table->string('field_name');
            $table->text('note')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('flagged_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokir_revision_flaggeds');
    }
};
