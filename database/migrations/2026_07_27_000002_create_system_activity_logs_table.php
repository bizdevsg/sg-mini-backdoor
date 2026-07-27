<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('system_activity_logs')) {
            Schema::create('system_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('category', 20)->index();
                $table->string('event', 80);
                $table->string('description', 255);
                $table->json('context')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['category', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_activity_logs');
    }
};
