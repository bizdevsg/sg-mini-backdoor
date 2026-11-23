<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_area_tiktok_videos', function (Blueprint $table) {
            $table->string('platform', 20)->default('youtube')->after('id')->index();
        });

        // Data lama: ID numerik berasal dari TikTok, selebihnya YouTube.
        DB::table('client_area_tiktok_videos')
            ->select(['id', 'video_id'])
            ->orderBy('id')
            ->each(function (object $row): void {
                if (ctype_digit((string) $row->video_id)) {
                    DB::table('client_area_tiktok_videos')->where('id', $row->id)->update(['platform' => 'tiktok']);
                }
            });
    }

    public function down(): void
    {
        Schema::table('client_area_tiktok_videos', function (Blueprint $table) {
            $table->dropColumn('platform');
        });
    }
};
