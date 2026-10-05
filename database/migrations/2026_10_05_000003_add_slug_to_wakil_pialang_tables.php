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
        $this->addSlug('wakil_pialang_categories', 'nama_kategori', 'kategori-wakil-pialang');
        $this->addSlug('wakil_pialangs', 'nama', 'wakil-pialang');
    }

    public function down(): void
    {
        foreach (['wakil_pialang_categories', 'wakil_pialangs'] as $tableName) {
            if (! Schema::hasColumn($tableName, 'slug')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }

    private function addSlug(string $tableName, string $sourceColumn, string $fallback): void
    {
        if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'slug')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->string('slug', 180)->nullable()->after('id');
        });

        $usedSlugs = [];

        foreach (DB::table($tableName)->orderBy('id')->get(['id', $sourceColumn]) as $row) {
            $base = Str::slug((string) $row->{$sourceColumn});
            $base = $base !== '' ? $base : $fallback;
            $slug = $base;
            $counter = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            $usedSlugs[] = $slug;

            DB::table($tableName)->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};
