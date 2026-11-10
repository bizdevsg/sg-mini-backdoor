<?php

namespace App\Jobs;

use App\Enums\PushNotificationStatus;
use App\Models\PushNotification;
use App\Support\MiniMicroNotificationClient;
use App\Support\SystemActivityLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\Jobs\SyncJob;
use RuntimeException;
use Throwable;

class SendPushNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * @var array<int, int>
     */
    public array $backoff = [30, 120];

    public function __construct(public int $pushNotificationId) {}

    public function handle(MiniMicroNotificationClient $client, SystemActivityLogger $logger): void
    {
        $notification = PushNotification::query()->find($this->pushNotificationId);

        // Idempotent: jangan pernah mengirim ulang notifikasi yang sudah selesai.
        if ($notification === null || $notification->status->isFinal()) {
            return;
        }

        $notification->increment('attempts');

        try {
            $response = $client->send($notification->payload, $notification->dry_run);
        } catch (ConnectionException $exception) {
            $this->retryOrFail($notification, $logger, 'Server Mini Micro tidak bisa dihubungi: '.$exception->getMessage());

            return;
        } catch (RuntimeException $exception) {
            $this->markFailed($notification, $logger, $exception->getMessage());

            return;
        }

        if (MiniMicroNotificationClient::wasSuccessful($response)) {
            $status = $notification->dry_run ? PushNotificationStatus::DryRun : PushNotificationStatus::Sent;

            $notification->forceFill([
                'status' => $status,
                'http_status' => $response->status(),
                'fcm_topic' => data_get($response->json(), 'data.fcmTopic'),
                'response' => $response->json(),
                'error_message' => null,
                'sent_at' => now(),
            ])->save();

            $this->log($logger, $notification, $status === PushNotificationStatus::Sent
                ? 'push_notification_sent'
                : 'push_notification_dry_run');

            return;
        }

        $notification->forceFill([
            'http_status' => $response->status(),
            'response' => $response->json(),
        ])->save();

        $message = MiniMicroNotificationClient::describeFailure($response);

        if (MiniMicroNotificationClient::isRetryable($response)) {
            $this->retryOrFail($notification, $logger, $message);

            return;
        }

        $this->markFailed($notification, $logger, $message);
    }

    public function failed(?Throwable $exception): void
    {
        $notification = PushNotification::query()->find($this->pushNotificationId);

        if ($notification === null || $notification->status->isFinal()) {
            return;
        }

        $this->markFailed(
            $notification,
            app(SystemActivityLogger::class),
            $exception?->getMessage() ?: 'Pengiriman gagal karena kesalahan tidak terduga.',
        );
    }

    private function retryOrFail(PushNotification $notification, SystemActivityLogger $logger, string $message): void
    {
        $canRetry = $this->job !== null
            && ! $this->job instanceof SyncJob
            && $this->attempts() < $this->tries;

        if (! $canRetry) {
            $this->markFailed($notification, $logger, $message);

            return;
        }

        $notification->forceFill([
            'status' => PushNotificationStatus::Queued,
            'error_message' => "Percobaan ke-{$this->attempts()} gagal, akan dicoba ulang otomatis. {$message}",
        ])->save();

        $this->release($this->backoff[$this->attempts() - 1] ?? end($this->backoff));
    }

    private function markFailed(PushNotification $notification, SystemActivityLogger $logger, string $message): void
    {
        $notification->forceFill([
            'status' => PushNotificationStatus::Failed,
            'error_message' => $message,
        ])->save();

        $this->log($logger, $notification, 'push_notification_failed');
    }

    private function log(SystemActivityLogger $logger, PushNotification $notification, string $event): void
    {
        $description = match ($event) {
            'push_notification_sent' => "Broadcast notifikasi {$notification->topic->value} terkirim dari backdoor.",
            'push_notification_dry_run' => "Dry run broadcast notifikasi {$notification->topic->value} dari backdoor.",
            default => "Broadcast notifikasi {$notification->topic->value} gagal dari backdoor.",
        };

        $logger->log(
            category: 'data',
            event: $event,
            description: $description,
            subject: 'push-notifications',
            user: $notification->user,
            context: [
                'module' => 'push-notifications',
                'push_notification_id' => $notification->id,
                'topic' => $notification->topic->value,
                'title' => $notification->title,
                'source' => $notification->sourceLabel(),
                'dry_run' => $notification->dry_run,
                'http_status' => $notification->http_status,
                'fcm_topic' => $notification->fcm_topic,
                'error_message' => $notification->error_message,
            ],
        );
    }
}
