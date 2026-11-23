<?php

namespace App\Models;

use Database\Factories\ClientAreaTiktokVideoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'platform',
    'video_id',
    'video_url',
    'file_path',
    'title',
    'is_active',
])]
class ClientAreaTiktokVideo extends Model
{
    /** @use HasFactory<ClientAreaTiktokVideoFactory> */
    use HasFactory;

    protected $table = 'client_area_tiktok_videos';

    protected $attributes = [
        'platform' => self::PLATFORM_YOUTUBE,
        'is_active' => false,
    ];

    public const PLATFORM_YOUTUBE = 'youtube';

    public const PLATFORM_TIKTOK = 'tiktok';

    public const PLATFORMS = [self::PLATFORM_YOUTUBE, self::PLATFORM_TIKTOK];

    /**
     * Deteksi platform dan ID video dari URL (YouTube embed/watch/youtu.be/shorts,
     * TikTok video/player URL) atau kode embed (<iframe> YouTube, <blockquote> TikTok).
     *
     * @return array{platform: string, video_id: string, video_url: string}|null
     */
    public static function detect(string $input): ?array
    {
        $input = trim($input);

        if (preg_match('~<iframe[^>]*\ssrc\s*=\s*[\x22\x27]([^\x22\x27]+)[\x22\x27]~i', $input, $matches) === 1) {
            $input = trim(html_entity_decode($matches[1]));
        } elseif (preg_match('~<blockquote[^>]*\scite\s*=\s*[\x22\x27]([^\x22\x27]+)[\x22\x27]~i', $input, $matches) === 1) {
            $input = trim(html_entity_decode($matches[1]));
        }

        $youtube = '~^(?:https?:)?//(?:www\.|m\.)?(?:youtube(?:-nocookie)?\.com/(?:embed/|shorts/|live/|watch\?(?:[^#]*&)?v=)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[/?&#].*)?$~i';

        if (preg_match($youtube, $input, $matches) === 1) {
            return [
                'platform' => self::PLATFORM_YOUTUBE,
                'video_id' => $matches[1],
                'video_url' => self::embedUrl(self::PLATFORM_YOUTUBE, $matches[1]),
            ];
        }

        $tiktok = '~^https?://(?:www\.|m\.)?tiktok\.com/(?:@[^/]+/video|player/v1)/([0-9]{1,30})(?:[/?#].*)?$~i';

        if (preg_match($tiktok, $input, $matches) === 1) {
            return [
                'platform' => self::PLATFORM_TIKTOK,
                'video_id' => $matches[1],
                'video_url' => $input,
            ];
        }

        return null;
    }

    public static function embedUrl(string $platform, string $videoId): string
    {
        return $platform === self::PLATFORM_TIKTOK
            ? "https://www.tiktok.com/player/v1/{$videoId}"
            : "https://www.youtube.com/embed/{$videoId}";
    }

    public static function autoplayEmbedUrl(string $platform, string $videoId): string
    {
        $query = $platform === self::PLATFORM_TIKTOK
            ? 'autoplay=1&muted=1'
            : 'autoplay=1&mute=1&playsinline=1&rel=0';

        return self::embedUrl($platform, $videoId).'?'.$query;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
