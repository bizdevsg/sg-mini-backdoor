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
        if (! Schema::hasTable('signals')) {
            return;
        }

        if (! Schema::hasColumn('signals', 'uuid')) {
            Schema::table('signals', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
        }

        DB::table('signals')
            ->whereNull('uuid')
            ->chunkById(100, function ($signals): void {
                foreach ($signals as $signal) {
                    DB::table('signals')
                        ->where('id', $signal->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });

        Schema::table('signals', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });

        if (Schema::hasColumn('signals', 'slug')) {
            Schema::table('signals', function (Blueprint $table) {
                $table->dropUnique(['slug']);
            });

            Schema::table('signals', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('signals')) {
            return;
        }

        if (! Schema::hasColumn('signals', 'slug')) {
            Schema::table('signals', function (Blueprint $table) {
                $table->string('slug', 180)->nullable()->after('category_id');
            });

            DB::table('signals')
                ->select('id')
                ->orderBy('id')
                ->chunkById(100, function ($signals): void {
                    foreach ($signals as $signal) {
                        DB::table('signals')
                            ->where('id', $signal->id)
                            ->update(['slug' => 'signal-'.$signal->id]);
                    }
                });

            Schema::table('signals', function (Blueprint $table) {
                $table->unique('slug');
                $table->string('slug', 180)->nullable(false)->change();
            });
        }

        if (Schema::hasColumn('signals', 'uuid')) {
            Schema::table('signals', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
            });

            Schema::table('signals', function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
};
