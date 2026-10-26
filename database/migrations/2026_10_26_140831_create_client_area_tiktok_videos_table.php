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
        Schema::create('client_area_tiktok_videos', function (Blueprint $table) {
            $table->id();
            $table->string('video_id', 30)->index();
            $table->string('video_url', 500);
            $table->string('title', 150);
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_area_tiktok_videos');
    }
};
