<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokir_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokir_id')->constrained('pokirs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('action', [
                'created',
                'updated',
                'submitted',
                'revision_requested',
                'verified',
                'finalized',
                'exported',
                'cancelled',
                'restored',
            ]);
            $table->json('changes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokir_activities');
    }
};
