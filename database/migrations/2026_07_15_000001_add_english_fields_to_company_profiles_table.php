<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->text('description_en')->nullable()->after('description');
            $table->json('mission_en')->nullable()->after('mission');
            $table->json('vision_en')->nullable()->after('vision');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'description_en',
                'mission_en',
                'vision_en',
            ]);
        });
    }
};
