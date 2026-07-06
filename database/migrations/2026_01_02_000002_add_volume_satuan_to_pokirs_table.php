<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pokirs', function (Blueprint $table) {
            $table->decimal('volume', 12, 2)->nullable()->after('notes');
            $table->string('satuan', 50)->nullable()->after('volume');
            $table->string('satuan_custom', 100)->nullable()->after('satuan');
        });
    }

    public function down(): void
    {
        Schema::table('pokirs', function (Blueprint $table) {
            $table->dropColumn(['volume', 'satuan', 'satuan_custom']);
        });
    }
};
