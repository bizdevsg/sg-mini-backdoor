<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('beritas')) {
            Schema::create('beritas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('berita_category_id')->constrained('berita_categories')->cascadeOnDelete();
                $table->string('author', 100)->nullable();
                $table->string('source', 150)->nullable();
                $table->string('title_id', 150)->nullable();
                $table->string('title_en', 150)->nullable();
                $table->string('slug', 160)->unique();
                $table->longText('content_id')->nullable();
                $table->longText('content_en')->nullable();
                $table->text('image')->nullable();
                $table->timestamps();

                $table->index(['berita_category_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
