<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Create tabel users sesuai ERD E-Pokir (SSO-based, tanpa password).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('sso_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table
                ->enum('role', ['admin', 'setwan', 'dewan'])
                ->default('dewan');
            $table->unsignedBigInteger('dapil_id')->nullable();
            $table->string('dapil_nama')->nullable();
            $table->unsignedBigInteger('fraksi_id')->nullable();
            $table->string('fraksi_nama')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('sso_token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::dropIfExists('password_reset_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
