<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->boolean('is_notification')->default(false)->index()->after('image');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('is_notification')->default(false)->index()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('is_notification');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('is_notification');
        });
    }
};
