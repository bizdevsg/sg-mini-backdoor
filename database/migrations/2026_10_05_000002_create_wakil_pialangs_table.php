<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wakil_pialangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('no_identitas', 50)->unique();
            $table->foreignId('id_kategori')
                ->constrained('wakil_pialang_categories')
                ->restrictOnDelete();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wakil_pialangs');
    }
};
