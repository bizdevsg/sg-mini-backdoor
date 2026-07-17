<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->foreignId('ebook_category_id')
                ->nullable()
                ->after('title')
                ->constrained('ebook_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        $categories = DB::table('ebooks')
            ->select('kategori')
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter();

        foreach ($categories as $categoryName) {
            $slug = \Illuminate\Support\Str::slug((string) $categoryName);

            $categoryId = DB::table('ebook_categories')->insertGetId([
                'name' => $categoryName,
                'slug' => $slug === '' ? 'kategori-' . now()->timestamp : $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('ebooks')
                ->where('kategori', $categoryName)
                ->update(['ebook_category_id' => $categoryId]);
        }

        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropIndex(['kategori']);
            $table->dropIndex(['kategori', 'created_at']);
            $table->dropColumn('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('kategori', 100)->nullable()->after('title');
        });

        DB::table('ebooks')
            ->leftJoin('ebook_categories', 'ebooks.ebook_category_id', '=', 'ebook_categories.id')
            ->update([
                'ebooks.kategori' => DB::raw('ebook_categories.name'),
            ]);

        Schema::table('ebooks', function (Blueprint $table) {
            $table->index('kategori');
            $table->index(['kategori', 'created_at']);
            $table->dropConstrainedForeignId('ebook_category_id');
        });
    }
};
