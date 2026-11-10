<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('banners')->where('type', 'regular')->update(['type' => 'mini_micro']);
    }

    public function down(): void
    {
        // The original types cannot be distinguished after conversion.
    }
};
