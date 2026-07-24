<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('signals')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            if (Schema::hasColumn('signals', 'sort_order')) {
                $table->dropColumn('sort_order');
            }

            if (Schema::hasColumn('signals', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('signals')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            if (! Schema::hasColumn('signals', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('image');
            }

            if (! Schema::hasColumn('signals', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            }
        });
    }
};
