<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('signals') || Schema::hasColumn('signals', 'slug')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            $table->string('slug', 180)->nullable()->after('category_id');
        });

        $signals = DB::table('signals')
            ->join('signal_categories', 'signal_categories.id', '=', 'signals.category_id')
            ->select('signals.id', 'signals.potensi', 'signals.timeframe', 'signal_categories.name as category_name')
            ->get();

        $usedSlugs = [];

        foreach ($signals as $signal) {
            $base = Str::slug($signal->category_name . '-' . $signal->potensi . '-' . $signal->timeframe);
            $base = $base !== '' ? $base : 'signal';
            $slug = $base;
            $counter = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base . '-' . $counter;
                $counter++;
            }

            $usedSlugs[] = $slug;

            DB::table('signals')->where('id', $signal->id)->update(['slug' => $slug]);
        }

        Schema::table('signals', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('signals') || ! Schema::hasColumn('signals', 'slug')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
