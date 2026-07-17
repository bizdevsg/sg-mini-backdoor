<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            return;
        }

        $columnsToDrop = collect([
            'title',
            'subtitle',
            'slug',
            'cta_label',
            'cta_url',
        ])->filter(fn (string $column): bool => Schema::hasColumn('banners', $column))
            ->values()
            ->all();

        if ($columnsToDrop !== []) {
            Schema::table('banners', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (! Schema::hasColumn('banners', 'title')) {
                $table->string('title', 150)->nullable()->after('id');
            }

            if (! Schema::hasColumn('banners', 'subtitle')) {
                $table->string('subtitle', 500)->nullable()->after('title');
            }

            if (! Schema::hasColumn('banners', 'slug')) {
                $table->string('slug')->nullable()->after('subtitle');
            }

            if (! Schema::hasColumn('banners', 'cta_label')) {
                $table->string('cta_label', 50)->nullable()->after('slug');
            }

            if (! Schema::hasColumn('banners', 'cta_url')) {
                $table->string('cta_url', 500)->nullable()->after('cta_label');
            }
        });
    }
};
