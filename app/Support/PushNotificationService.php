<?php

namespace App\Support;

use App\Enums\PushNotificationStatus;
use App\Enums\PushNotificationTopic;
use App\Jobs\SendPushNotification;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PushNotificationService
{
    /**
     * @return array{topic: PushNotificationTopic, title: string, description: string, source_type: string, source_id: int, source: Model}|null
     */
    public function draftFromSource(?string $sourceType, ?int $sourceId): ?array
    {
        $source = $this->findSource($sourceType, $sourceId);

        if ($source === null) {
            return null;
        }

        [$title, $description] = match (true) {
            $source instanceof Berita => [
                (string) $source->title_id,
                (string) $source->content_id,
            ],
            $source instanceof Banner => [
                (string) $source->title,
                (string) $source->terms_and_conditions,
            ],
        };

        return [
            'topic' => PushNotificationTopic::forSource((string) $sourceType),
            'title' => self::truncate($title, MiniMicroNotificationClient::TITLE_MAX),
            'description' => self::truncate($description, MiniMicroNotificationClient::DESCRIPTION_MAX),
            'source_type' => (string) $sourceType,
            'source_id' => (int) $sourceId,
            'source' => $source,
        ];
    }

    public function findSource(?string $sourceType, ?int $sourceId): Berita|Banner|null
    {
        if ($sourceId === null) {
            return null;
        }

        return match ($sourceType) {
            PushNotification::SOURCE_BERITA => Berita::query()->with('category')->find($sourceId),
            PushNotification::SOURCE_BANNER => Banner::query()->find($sourceId),
            default => null,
        };
    }

    /**
     * @param  array{topic: string, title: string, description: string, source_type?: string|null, source_id?: int|null}  $data
     */
    public function dispatchBroadcast(User $user, array $data): PushNotification
    {
        $topic = PushNotificationTopic::from($data['topic']);
        $sourceType = $data['source_type'] ?? null;
        $sourceId = isset($data['source_id']) ? (int) $data['source_id'] : null;

        $payload = [
            'topic' => $topic->value,
            'title' => $data['title'],
            'description' => $data['description'],
            'broadcast' => true,
        ];

        if ($sourceType === PushNotification::SOURCE_BERITA && $sourceId !== null) {
            $payload['news_id'] = $sourceId;
        }

        if ($sourceType === PushNotification::SOURCE_BANNER && $sourceId !== null) {
            $payload['promotion_id'] = $sourceId;
        }

        $notification = PushNotification::query()->create([
            'user_id' => $user->id,
            'topic' => $topic,
            'title' => $data['title'],
            'description' => $data['description'],
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'payload' => $payload,
            'dry_run' => MiniMicroNotificationClient::isDryRun(),
            'status' => PushNotificationStatus::Queued,
        ]);

        SendPushNotification::dispatch($notification->id);

        return $notification;
    }

    public function retry(PushNotification $notification): void
    {
        $notification->forceFill([
            'status' => PushNotificationStatus::Queued,
            'dry_run' => MiniMicroNotificationClient::isDryRun(),
            'error_message' => null,
        ])->save();

        SendPushNotification::dispatch($notification->id);
    }

    /**
     * Sisa detik sebelum broadcast sungguhan berikutnya boleh dikirim.
     * Dry run tidak terkena jeda.
     */
    public function cooldownRemainingSeconds(): int
    {
        if (MiniMicroNotificationClient::isDryRun()) {
            return 0;
        }

        $minutes = max(0, (int) config('services.mini_micro.broadcast_cooldown_minutes', 5));

        if ($minutes === 0) {
            return 0;
        }

        $last = PushNotification::query()
            ->live()
            ->whereIn('status', [PushNotificationStatus::Queued, PushNotificationStatus::Sent])
            ->latest('created_at')
            ->first();

        if ($last === null) {
            return 0;
        }

        $availableAt = $last->created_at->copy()->addMinutes($minutes);

        return now()->lt($availableAt) ? (int) ceil(now()->diffInSeconds($availableAt, true)) : 0;
    }

    /**
     * Broadcast sungguhan terakhir yang berhasil untuk konten sumber.
     */
    public function lastLiveSendFor(string $sourceType, int $sourceId): ?PushNotification
    {
        return PushNotification::query()
            ->live()
            ->forSource($sourceType, $sourceId)
            ->whereIn('status', [PushNotificationStatus::Queued, PushNotificationStatus::Sent])
            ->with('user')
            ->latest('created_at')
            ->first();
    }

    /**
     * Ubah HTML editor menjadi teks satu baris dan potong rapi di batas kata.
     */
    public static function truncate(string $value, int $limit): string
    {
        $text = html_entity_decode(strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], ' ', $value)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim((string) preg_replace('/\s+/u', ' ', str_replace("\u{00A0}", ' ', $text)));

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $cut = mb_substr($text, 0, $limit - 1);
        $lastSpace = mb_strrpos($cut, ' ');

        if ($lastSpace !== false && $lastSpace > (int) ($limit * 0.6)) {
            $cut = mb_substr($cut, 0, $lastSpace);
        }

        return Str::of($cut)->rtrim(' ,.;:-')->append('…')->toString();
    }
}
