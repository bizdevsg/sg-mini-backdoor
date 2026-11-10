<?php

namespace App\Enums;

enum PushNotificationTopic: string
{
    case Announcement = 'Announcement';
    case Promotion = 'Promotion';

    public function label(): string
    {
        return match ($this) {
            self::Announcement => 'Pengumuman (Announcement)',
            self::Promotion => 'Promosi (Promotion)',
        };
    }

    /**
     * Topic yang dipakai otomatis untuk konten sumber.
     */
    public static function forSource(string $sourceType): self
    {
        return $sourceType === 'banner' ? self::Promotion : self::Announcement;
    }
}
