<?php

namespace App\Models;

use App\Enums\PushNotificationStatus;
use App\Enums\PushNotificationTopic;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'topic',
    'title',
    'description',
    'source_type',
    'source_id',
    'payload',
    'dry_run',
    'status',
    'attempts',
    'http_status',
    'fcm_topic',
    'response',
    'error_message',
    'sent_at',
])]
class PushNotification extends Model
{
    public const SOURCE_BERITA = 'berita';

    public const SOURCE_BANNER = 'banner';

    protected $attributes = [
        'status' => 'queued',
        'dry_run' => true,
        'attempts' => 0,
    ];

    protected function casts(): array
    {
        return [
            'topic' => PushNotificationTopic::class,
            'status' => PushNotificationStatus::class,
            'payload' => 'array',
            'response' => 'array',
            'dry_run' => 'boolean',
            'attempts' => 'integer',
            'http_status' => 'integer',
            'source_id' => 'integer',
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Broadcast sungguhan (bukan dry run) yang sudah atau sedang dikirim.
     */
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('dry_run', false);
    }

    public function scopeForSource(Builder $query, string $sourceType, int $sourceId): Builder
    {
        return $query->where('source_type', $sourceType)->where('source_id', $sourceId);
    }

    public function sourceModel(): ?Model
    {
        return match ($this->source_type) {
            self::SOURCE_BERITA => Berita::query()->with('category')->find($this->source_id),
            self::SOURCE_BANNER => Banner::query()->find($this->source_id),
            default => null,
        };
    }

    public function sourceLabel(): string
    {
        return match ($this->source_type) {
            self::SOURCE_BERITA => 'Berita #'.$this->source_id,
            self::SOURCE_BANNER => 'Banner #'.$this->source_id,
            default => 'Manual',
        };
    }
}
