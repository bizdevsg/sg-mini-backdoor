<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Client HTTP untuk endpoint push notification Mini Micro.
 *
 * POST {base_url}/system/private/send-manual-notification, dijaga header X-Api-Key.
 * Saat dry run, request dikirim dengan ?logOnly=true sehingga server tetap
 * memvalidasi API key dan payload, tetapi FCM tidak dipanggil.
 */
class MiniMicroNotificationClient
{
    public const ENDPOINT = '/system/private/send-manual-notification';

    public const TITLE_MAX = 40;

    public const DESCRIPTION_MAX = 150;

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws RuntimeException jika API key belum diatur.
     * @throws ConnectionException jika server tidak bisa dihubungi.
     */
    public function send(array $payload, bool $dryRun): Response
    {
        $apiKey = (string) config('services.mini_micro.api_key');

        if ($apiKey === '') {
            throw new RuntimeException('API key Mini Micro belum diatur. Isi MINI_MICRO_API_KEY pada file .env.');
        }

        return Http::baseUrl(rtrim((string) config('services.mini_micro.base_url'), '/'))
            ->acceptJson()
            ->asJson()
            ->withHeaders(['X-Api-Key' => $apiKey])
            ->timeout(max(1, (int) config('services.mini_micro.timeout', 15)))
            ->when($dryRun, fn ($request) => $request->withQueryParameters(['logOnly' => 'true']))
            ->post(self::ENDPOINT, $payload);
    }

    public static function isDryRun(): bool
    {
        return (bool) config('services.mini_micro.dry_run', true);
    }

    public static function baseUrlHost(): string
    {
        return (string) (parse_url((string) config('services.mini_micro.base_url'), PHP_URL_HOST) ?: '-');
    }

    /**
     * Pesan error yang mudah dipahami admin untuk setiap kode status Mini Micro.
     */
    public static function describeFailure(Response $response): string
    {
        $serverMessage = trim((string) (data_get($response->json(), 'headers.message') ?? ''));

        $status = self::effectiveStatus($response);
        $message = match ($status) {
            400 => 'Payload atau topic ditolak server (400).',
            401 => 'API key ditolak server (401). Periksa MINI_MICRO_API_KEY.',
            404 => 'Server Mini Micro merespons 404. Periksa alamat dan endpoint notifikasi UAT.',
            502 => 'Server gagal meneruskan ke Firebase/FCM (502).',
            503 => 'API key belum dikonfigurasi di server Mini Micro (503).',
            default => "Server Mini Micro merespons dengan status {$status}.",
        };

        return $serverMessage !== '' ? "{$message} Pesan server: {$serverMessage}" : $message;
    }

    /**
     * Status yang layak dicoba ulang otomatis.
     */
    public static function isRetryable(Response $response): bool
    {
        $status = self::effectiveStatus($response);

        return in_array($status, [429, 502, 503, 504], true) || $status >= 500;
    }

    public static function effectiveStatus(Response $response): int
    {
        if (! $response->successful()) {
            return $response->status();
        }

        $status = data_get($response->json(), 'headers.statusCode');

        return is_numeric($status) ? (int) $status : $response->status();
    }

    public static function wasSuccessful(Response $response): bool
    {
        $status = self::effectiveStatus($response);

        return $response->successful() && $status >= 200 && $status < 300;
    }
}
