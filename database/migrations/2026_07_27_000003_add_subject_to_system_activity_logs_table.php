<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('system_activity_logs') || Schema::hasColumn('system_activity_logs', 'subject')) {
            return;
        }

        Schema::table('system_activity_logs', function (Blueprint $table) {
            $table->string('subject', 60)->nullable();
            $table->index(
                ['category', 'subject', 'created_at'],
                'system_activity_logs_category_subject_created_at_index',
            );
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('system_activity_logs') || ! Schema::hasColumn('system_activity_logs', 'subject')) {
            return;
        }

        Schema::table('system_activity_logs', function (Blueprint $table) {
            $table->dropIndex('system_activity_logs_category_subject_created_at_index');
            $table->dropColumn('subject');
        });
    }
};
