<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_area_settings') || ! Schema::hasColumn('client_area_settings', 'rate_limit_public_api')) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) {
            $table->dropColumn('rate_limit_public_api');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_area_settings') || Schema::hasColumn('client_area_settings', 'rate_limit_public_api')) {
            return;
        }

        Schema::table('client_area_settings', function (Blueprint $table) {
            $table->unsignedInteger('rate_limit_public_api')->default(60)->after('allowed_origin_frontend');
        });
    }
};
