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
        Schema::create('tradingview_symbols', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('symbol_ws', 100)->unique();
            $table->string('symbol_tv', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tradingview_symbols');
    }
};
