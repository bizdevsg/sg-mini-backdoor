<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_area_tiktok_videos', function (Blueprint $table): void {
            $table->string('file_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('client_area_tiktok_videos', function (Blueprint $table): void {
            $table->dropColumn('file_path');
        });
    }
};
