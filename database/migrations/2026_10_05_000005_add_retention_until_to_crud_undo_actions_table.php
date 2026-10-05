<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('crud_undo_actions') || Schema::hasColumn('crud_undo_actions', 'retention_until')) {
            return;
        }

        Schema::table('crud_undo_actions', function (Blueprint $table) {
            $table->timestamp('retention_until')->after('expires_at')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('crud_undo_actions') || ! Schema::hasColumn('crud_undo_actions', 'retention_until')) {
            return;
        }

        Schema::table('crud_undo_actions', function (Blueprint $table) {
            $table->dropColumn('retention_until');
        });
    }
};
