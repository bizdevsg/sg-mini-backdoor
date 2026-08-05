<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('signals')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            if (! Schema::hasColumn('signals', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('signal_categories')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('signals', 'potensi')) {
                $table->enum('potensi', ['buy', 'sell'])
                    ->default('buy')
                    ->after('category_id');
            }

            if (! Schema::hasColumn('signals', 'timeframe')) {
                $table->enum('timeframe', ['15M', '30M', '1H', '4H', '1D'])
                    ->default('15M')
                    ->after('potensi');
            }

            if (! Schema::hasColumn('signals', 'taking_profit')) {
                $table->string('taking_profit', 100)
                    ->default('')
                    ->after('timeframe');
            }

            if (! Schema::hasColumn('signals', 'stop_loss')) {
                $table->string('stop_loss', 100)
                    ->default('')
                    ->after('taking_profit');
            }

            if (! Schema::hasColumn('signals', 'sumber')) {
                $table->string('sumber', 150)
                    ->default('')
                    ->after('stop_loss');
            }
        });

        if (Schema::hasColumn('signals', 'signal_category_id') && Schema::hasColumn('signals', 'category_id')) {
            DB::table('signals')
                ->whereNull('category_id')
                ->update([
                    'category_id' => DB::raw('signal_category_id'),
                ]);
        }

        if (Schema::hasColumn('signals', 'source') && Schema::hasColumn('signals', 'sumber')) {
            DB::table('signals')
                ->where('sumber', '')
                ->update([
                    'sumber' => DB::raw('COALESCE(source, "")'),
                ]);
        }

        if (Schema::hasColumn('signals', 'signal_category_id')) {
            try {
                Schema::table('signals', function (Blueprint $table) {
                    $table->dropForeign(['signal_category_id']);
                });
            } catch (Throwable) {
                // Legacy schemas may not have a named foreign key to drop.
            }
        }

        $columnsToDrop = array_values(array_filter([
            Schema::hasColumn('signals', 'signal_category_id') ? 'signal_category_id' : null,
            Schema::hasColumn('signals', 'author') ? 'author' : null,
            Schema::hasColumn('signals', 'source') ? 'source' : null,
            Schema::hasColumn('signals', 'title_id') ? 'title_id' : null,
            Schema::hasColumn('signals', 'title_en') ? 'title_en' : null,
            Schema::hasColumn('signals', 'slug') ? 'slug' : null,
            Schema::hasColumn('signals', 'content_id') ? 'content_id' : null,
            Schema::hasColumn('signals', 'content_en') ? 'content_en' : null,
        ]));

        if ($columnsToDrop !== []) {
            Schema::table('signals', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }

        try {
            Schema::table('signals', function (Blueprint $table) {
                $table->index(['category_id', 'created_at'], 'signals_category_id_created_at_index');
            });
        } catch (Throwable) {
            // Index may already exist on partially migrated environments.
        }
    }

    public function down(): void
    {
        // Forward-fix only. This migration replaces the legacy content-based
        // signal schema with trading setup fields.
    }
};
