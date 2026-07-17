<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners') || Schema::hasColumn('banners', 'slug')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            $table->string('slug', 160)->nullable()->after('id');
        });

        DB::table('banners')
            ->select('id')
            ->orderBy('id')
            ->get()
            ->each(function (object $banner): void {
                DB::table('banners')
                    ->where('id', $banner->id)
                    ->update([
                        'slug' => 'banner-' . $banner->id,
                    ]);
            });

        Schema::table('banners', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('banners') || ! Schema::hasColumn('banners', 'slug')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            $table->dropUnique('banners_slug_unique');
            $table->dropColumn('slug');
        });
    }
};
