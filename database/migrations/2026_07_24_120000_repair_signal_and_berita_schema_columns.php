<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->repairSignalsTable();
        $this->repairBeritasTable();
    }

    public function down(): void
    {
        // Forward-fix only. This migration repairs legacy schemas in place.
    }

    private function repairSignalsTable(): void
    {
        if (! Schema::hasTable('signals')) {
            return;
        }

        Schema::table('signals', function (Blueprint $table) {
            if (! Schema::hasColumn('signals', 'author')) {
                $table->string('author', 100)->nullable()->after('signal_category_id');
            }

            if (! Schema::hasColumn('signals', 'source')) {
                $table->string('source', 150)->nullable()->after('author');
            }

            if (! Schema::hasColumn('signals', 'title_id')) {
                $table->string('title_id', 150)->nullable()->after('source');
            }

            if (! Schema::hasColumn('signals', 'title_en')) {
                $table->string('title_en', 150)->nullable()->after('title_id');
            }

            if (! Schema::hasColumn('signals', 'content_id')) {
                $table->longText('content_id')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('signals', 'content_en')) {
                $table->longText('content_en')->nullable()->after('content_id');
            }
        });

        if (Schema::hasColumn('signals', 'title')) {
            DB::table('signals')
                ->where(function ($query) {
                    $query->whereNull('title_id')->orWhere('title_id', '');
                })
                ->update(['title_id' => DB::raw('title')]);

            DB::table('signals')
                ->where(function ($query) {
                    $query->whereNull('title_en')->orWhere('title_en', '');
                })
                ->update(['title_en' => DB::raw('title')]);
        }

        if (Schema::hasColumn('signals', 'content')) {
            DB::table('signals')
                ->where(function ($query) {
                    $query->whereNull('content_id')->orWhere('content_id', '');
                })
                ->update(['content_id' => DB::raw('content')]);

            DB::table('signals')
                ->where(function ($query) {
                    $query->whereNull('content_en')->orWhere('content_en', '');
                })
                ->update(['content_en' => DB::raw('content')]);
        }

    }

    private function repairBeritasTable(): void
    {
        if (! Schema::hasTable('beritas')) {
            return;
        }

        Schema::table('beritas', function (Blueprint $table) {
            if (! Schema::hasColumn('beritas', 'author')) {
                $table->string('author', 100)->nullable()->after('berita_category_id');
            }

            if (! Schema::hasColumn('beritas', 'source')) {
                $table->string('source', 150)->nullable()->after('author');
            }

            if (! Schema::hasColumn('beritas', 'title_id')) {
                $table->string('title_id', 150)->nullable()->after('source');
            }

            if (! Schema::hasColumn('beritas', 'title_en')) {
                $table->string('title_en', 150)->nullable()->after('title_id');
            }

            if (! Schema::hasColumn('beritas', 'content_id')) {
                $table->longText('content_id')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('beritas', 'content_en')) {
                $table->longText('content_en')->nullable()->after('content_id');
            }
        });

        if (Schema::hasColumn('beritas', 'title')) {
            DB::table('beritas')
                ->where(function ($query) {
                    $query->whereNull('title_id')->orWhere('title_id', '');
                })
                ->update(['title_id' => DB::raw('title')]);

            DB::table('beritas')
                ->where(function ($query) {
                    $query->whereNull('title_en')->orWhere('title_en', '');
                })
                ->update(['title_en' => DB::raw('title')]);
        }

        if (Schema::hasColumn('beritas', 'content')) {
            DB::table('beritas')
                ->where(function ($query) {
                    $query->whereNull('content_id')->orWhere('content_id', '');
                })
                ->update(['content_id' => DB::raw('content')]);

            DB::table('beritas')
                ->where(function ($query) {
                    $query->whereNull('content_en')->orWhere('content_en', '');
                })
                ->update(['content_en' => DB::raw('content')]);
        }
    }
};
