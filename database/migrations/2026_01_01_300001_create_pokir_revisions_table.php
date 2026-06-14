<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokir_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokir_id')->constrained('pokirs')->cascadeOnDelete();
            $table->foreignId('pokir_activity_id')->constrained('pokir_activities')->cascadeOnDelete();
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokir_revisions');
    }
};
