<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirasi_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirasi_id')->constrained('aspirasis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('action', [
                'created',
                'updated',
                'completed',
                'used_in_pokir',
                'removed_from_pokir',
                'deleted',
                'restored',
            ]);
            $table->json('changes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasi_activities');
    }
};
