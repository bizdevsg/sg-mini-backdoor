<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crud_undo_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token_hash', 64)->unique();
            $table->string('route_name', 100);
            $table->string('action', 20);
            $table->string('model_type');
            $table->string('model_key', 191)->nullable();
            $table->mediumText('before_snapshot')->nullable();
            $table->mediumText('after_snapshot')->nullable();
            $table->mediumText('files')->nullable();
            $table->text('redirect_url');
            $table->timestamp('expires_at')->index();
            $table->timestamp('retention_until')->index();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crud_undo_actions');
    }
};
