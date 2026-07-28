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

        $hasApiEnabled = Schema::hasColumn('client_area_settings', 'api_enabled');
        $hasApiKeyRotationNotice = Schema::hasColumn('client_area_settings', 'api_key_rotation_notice');
        $hasAllowedOriginFrontend = Schema::hasColumn('client_area_settings', 'allowed_origin_frontend');

        Schema::table('client_area_settings', function (Blueprint $table) use (
            $hasApiEnabled,
            $hasApiKeyRotationNotice,
            $hasAllowedOriginFrontend
        ) {
            if (! $hasApiEnabled) {
                $table->boolean('api_enabled')->default(true)->after('tawk_to_prod');
            }

            if (! $hasApiKeyRotationNotice) {
                $table->string('api_key_rotation_notice')->nullable()->after('api_enabled');
            }

            if (! $hasAllowedOriginFrontend) {
                $table->text('allowed_origin_frontend')->nullable()->after('api_key_rotation_notice');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_area_settings')) {
            return;
        }

        $hasApiEnabled = Schema::hasColumn('client_area_settings', 'api_enabled');
        $hasApiKeyRotationNotice = Schema::hasColumn('client_area_settings', 'api_key_rotation_notice');
        $hasAllowedOriginFrontend = Schema::hasColumn('client_area_settings', 'allowed_origin_frontend');

        Schema::table('client_area_settings', function (Blueprint $table) use (
            $hasApiEnabled,
            $hasApiKeyRotationNotice,
            $hasAllowedOriginFrontend
        ) {
            $columns = [];

            if ($hasAllowedOriginFrontend) {
                $columns[] = 'allowed_origin_frontend';
            }

            if ($hasApiKeyRotationNotice) {
                $columns[] = 'api_key_rotation_notice';
            }

            if ($hasApiEnabled) {
                $columns[] = 'api_enabled';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
