<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wakil_pialang_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 150);
            $table->text('alamat_kantor_cabang');
            $table->string('telp', 30);
            $table->text('link_google_maps');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wakil_pialang_categories');
    }
};
