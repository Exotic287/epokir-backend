<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // doctrine/dbal tidak terinstall — Schema::table()->change() tidak bisa dipakai untuk kolom enum
    public function up(): void
    {
        DB::statement("ALTER TABLE aspirasi_activities MODIFY action ENUM(
            'created', 'updated', 'completed', 'used_in_pokir', 'removed_from_pokir',
            'deleted', 'restored', 'archived'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE aspirasi_activities MODIFY action ENUM(
            'created', 'updated', 'completed', 'used_in_pokir', 'removed_from_pokir',
            'deleted', 'restored'
        ) NOT NULL");
    }
};
