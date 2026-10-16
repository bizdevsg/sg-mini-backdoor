<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE `produks` MODIFY `kategori` ENUM('SPA', 'JFX', 'PRIME') NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::table('produks')->where('kategori', 'PRIME')->update(['kategori' => 'SPA']);
        DB::statement("ALTER TABLE `produks` MODIFY `kategori` ENUM('SPA', 'JFX') NOT NULL");
    }
};
