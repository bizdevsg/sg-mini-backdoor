<?php

namespace App\Models;

use Database\Factories\ClientAreaTiktokVideoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
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
        'is_active' => false,
    ];

    /**
     * Ambil ID video YouTube dari URL embed/watch/youtu.be atau kode <iframe> yang ditempel.
     */
    public static function extractYoutubeId(string $input): ?string
    {
        $input = trim($input);

        if (preg_match('~<iframe[^>]*\ssrc\s*=\s*["\']([^"\']+)["\']~i', $input, $matches) === 1) {
            $input = trim(html_entity_decode($matches[1]));
        }

        $pattern = '~^(?:https?:)?//(?:www\.|m\.)?(?:youtube(?:-nocookie)?\.com/(?:embed/|shorts/|live/|watch\?(?:[^#]*&)?v=)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[/?&#].*)?$~i';

        return preg_match($pattern, $input, $matches) === 1 ? $matches[1] : null;
    }

    public static function youtubeEmbedUrl(string $videoId): string
    {
        return "https://www.youtube.com/embed/{$videoId}";
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
