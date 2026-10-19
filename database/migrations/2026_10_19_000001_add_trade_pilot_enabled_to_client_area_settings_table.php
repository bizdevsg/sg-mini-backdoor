<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_area_settings')
            || Schema::hasColumn('client_area_settings', 'trade_pilot_enabled')) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) {
            $table->boolean('trade_pilot_enabled')->default(false)->after('tawk_to_prod');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_area_settings')
            || ! Schema::hasColumn('client_area_settings', 'trade_pilot_enabled')) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) {
            $table->dropColumn('trade_pilot_enabled');
        });
    }
};
