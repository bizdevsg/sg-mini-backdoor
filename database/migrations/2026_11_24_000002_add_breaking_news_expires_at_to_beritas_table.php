<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->timestamp('breaking_news_expires_at')->nullable()->index()->after('is_breaking_news');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('breaking_news_expires_at');
        });
    }
};
