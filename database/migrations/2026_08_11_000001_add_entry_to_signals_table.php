<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signals', function (Blueprint $table) {
            if (! Schema::hasColumn('signals', 'entry')) {
                $table->string('entry', 100)
                    ->default('')
                    ->after('timeframe');
            }
        });
    }

    public function down(): void
    {
        Schema::table('signals', function (Blueprint $table) {
            if (Schema::hasColumn('signals', 'entry')) {
                $table->dropColumn('entry');
            }
        });
    }
};
