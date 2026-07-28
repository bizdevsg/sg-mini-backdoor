<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_area_settings') || Schema::hasColumn('client_area_settings', 'tawk_to_enabled')) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) {
            $table->boolean('tawk_to_enabled')->default(false)->after('client_area_prod');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_area_settings') || ! Schema::hasColumn('client_area_settings', 'tawk_to_enabled')) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) {
            $table->dropColumn('tawk_to_enabled');
        });
    }
};
