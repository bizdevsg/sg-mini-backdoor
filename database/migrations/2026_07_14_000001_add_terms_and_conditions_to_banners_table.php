<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners') || Schema::hasColumn('banners', 'terms_and_conditions')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            $table->text('terms_and_conditions')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('banners') || ! Schema::hasColumn('banners', 'terms_and_conditions')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('terms_and_conditions');
        });
    }
};
