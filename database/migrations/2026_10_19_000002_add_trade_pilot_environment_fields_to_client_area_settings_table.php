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

        $hasTradePilotDev = Schema::hasColumn('client_area_settings', 'trade_pilot_dev');
        $hasTradePilotProd = Schema::hasColumn('client_area_settings', 'trade_pilot_prod');

        Schema::table('client_area_settings', function (Blueprint $table) use ($hasTradePilotDev, $hasTradePilotProd) {
            if (! $hasTradePilotDev) {
                $table->boolean('trade_pilot_dev')->nullable()->after('trade_pilot_enabled');
            }

            if (! $hasTradePilotProd) {
                $table->boolean('trade_pilot_prod')->nullable()->after('trade_pilot_dev');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_area_settings')) {
            return;
        }

        $columns = collect(['trade_pilot_dev', 'trade_pilot_prod'])
            ->filter(fn (string $column): bool => Schema::hasColumn('client_area_settings', $column))
            ->all();

        if ($columns === []) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
