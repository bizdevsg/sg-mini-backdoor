<?php

namespace App\Enums;

enum PushNotificationStatus: string
{
    case Queued = 'queued';
    case Sent = 'sent';
    case DryRun = 'dry_run';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Queued => 'Dalam antrean',
            self::Sent => 'Terkirim',
            self::DryRun => 'Dry run',
            self::Failed => 'Gagal',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Queued => 'border-black/10 bg-black/5 text-smoke',
            self::Sent => 'border-emerald-500/25 bg-emerald-500/10 text-emerald-700',
            self::DryRun => 'border-blue-500/30 bg-blue-500/10 text-blue-700',
            self::Failed => 'border-red-500/20 bg-red-500/5 text-red-700',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Sent, self::DryRun], true);
    }
}
