<?php

namespace App\Support;

use App\Models\SystemActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class SystemActivityLogger
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function log(
        string $category,
        string $event,
        string $description,
        ?string $subject = null,
        ?User $user = null,
        ?Request $request = null,
        array $context = [],
    ): void {
        SystemActivityLog::query()->create([
            'user_id' => $user?->id,
            'category' => $category,
            'subject' => $subject,
            'event' => $event,
            'description' => $description,
            'context' => $context,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
