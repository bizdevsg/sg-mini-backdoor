<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_area_settings')) {
            Schema::create('client_area_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('client_area_dev')->default(false);
                $table->boolean('client_area_prod')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_area_settings');
    }
};
