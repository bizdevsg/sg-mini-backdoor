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
            $table->text('slug')->change();
        });

        Schema::table('informasis', function (Blueprint $table) {
            $table->dropUnique('informasis_slug_unique');
            $table->text('slug')->change();
        });

        Schema::table('penghargaans', function (Blueprint $table) {
            $table->dropUnique('penghargaans_slug_unique');
            $table->text('slug')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->string('slug', 180)->change();
        });

        Schema::table('informasis', function (Blueprint $table) {
            $table->string('slug', 180)->change();
            $table->unique('slug');
        });

        Schema::table('penghargaans', function (Blueprint $table) {
            $table->string('slug', 180)->change();
            $table->unique('slug');
        });
    }
};
