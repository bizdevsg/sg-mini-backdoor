<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('signals') || Schema::hasColumn('signals', 'confident')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            $table->string('confident', 100)
                ->default('')
                ->after('timeframe');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('signals') || ! Schema::hasColumn('signals', 'confident')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            $table->dropColumn('confident');
        });
    }
};
