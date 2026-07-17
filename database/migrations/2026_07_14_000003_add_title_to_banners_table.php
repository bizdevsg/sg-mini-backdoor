<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners') || Schema::hasColumn('banners', 'title')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            $table->string('title', 150)->nullable()->after('id');
        });

        DB::table('banners')
            ->select('id')
            ->orderBy('id')
            ->get()
            ->each(function (object $banner): void {
                DB::table('banners')
                    ->where('id', $banner->id)
                    ->update([
                        'title' => 'Banner ' . $banner->id,
                    ]);
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('banners') || ! Schema::hasColumn('banners', 'title')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
