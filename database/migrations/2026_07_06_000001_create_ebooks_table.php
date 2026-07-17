<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('kategori', 100)->index();
            $table->string('slug')->unique();
            $table->longText('description');
            $table->text('image')->nullable();
            $table->text('file');
            $table->timestamps();

            $table->index(['kategori', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
