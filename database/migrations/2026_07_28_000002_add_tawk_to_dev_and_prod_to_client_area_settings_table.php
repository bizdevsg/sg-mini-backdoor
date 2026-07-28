<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_area_settings')) {
            return;
        }

        $hasTawkToDev = Schema::hasColumn('client_area_settings', 'tawk_to_dev');
        $hasTawkToProd = Schema::hasColumn('client_area_settings', 'tawk_to_prod');

        Schema::table('client_area_settings', function (Blueprint $table) use ($hasTawkToDev, $hasTawkToProd) {
            if (! $hasTawkToDev) {
                $table->boolean('tawk_to_dev')->nullable()->after('tawk_to_enabled');
            }

            if (! $hasTawkToProd) {
                $table->boolean('tawk_to_prod')->nullable()->after('tawk_to_dev');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_area_settings')) {
            return;
        }

        $hasTawkToDev = Schema::hasColumn('client_area_settings', 'tawk_to_dev');
        $hasTawkToProd = Schema::hasColumn('client_area_settings', 'tawk_to_prod');

        Schema::table('client_area_settings', function (Blueprint $table) use ($hasTawkToDev, $hasTawkToProd) {
            if ($hasTawkToProd) {
                $table->dropColumn('tawk_to_prod');
            }

            if ($hasTawkToDev) {
                $table->dropColumn('tawk_to_dev');
            }
        });
    }
};
