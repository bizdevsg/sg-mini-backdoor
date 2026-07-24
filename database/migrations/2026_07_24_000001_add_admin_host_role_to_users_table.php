<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', array_map(
                static fn (UserRole $role): string => $role->value,
                UserRole::cases()
            ))
                ->default(UserRole::Admin->value)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('role', UserRole::AdminHost->value)
            ->update(['role' => UserRole::Admin->value]);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                UserRole::Admin->value,
                UserRole::Superadmin->value,
            ])
                ->default(UserRole::Admin->value)
                ->change();
        });
    }
};
