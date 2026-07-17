<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->string('slug', 180)->change();
        });

        Schema::table('informasis', function (Blueprint $table) {
            $table->string('slug', 180)->change();
        });

        Schema::table('penghargaans', function (Blueprint $table) {
            $table->text('subtitle')->change();
            $table->string('slug', 180)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->text('slug')->change();
        });

        Schema::table('informasis', function (Blueprint $table) {
            $table->string('slug')->change();
        });

        Schema::table('penghargaans', function (Blueprint $table) {
            $table->string('subtitle', 255)->change();
            $table->string('slug')->change();
        });
    }
};
