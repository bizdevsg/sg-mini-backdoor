<?php

use App\Models\ClientAreaSetting;
use App\Models\SystemActivityLog;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('client area api returns dev and prod statuses', function () {
    config()->set('api-auth.key', 'test-api-key');

    ClientAreaSetting::query()->create([
        'client_area_dev' => true,
        'client_area_prod' => false,
        'tawk_to_enabled' => true,
        'tawk_to_dev' => true,
        'tawk_to_prod' => false,
        'api_enabled' => true,
        'api_key_rotation_notice' => 'Rotasi API key dijadwalkan 15 Agustus 2026.',
        'allowed_origin_frontend' => 'https://frontend-prod.test',
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.11'])
        ->withHeaders([
            ...apiKeyHeaders(),
            'Origin' => 'https://frontend-prod.test',
        ])
        ->getJson('/api/v1/client-area')
        ->assertSuccessful()
        ->assertJsonPath('data.dev', true)
        ->assertJsonPath('data.prod', false)
        ->assertJsonPath('data.tawk_to_dev', true)
        ->assertJsonPath('data.tawk_to_prod', false)
        ->assertHeader('X-API-Key-Rotation-Notice', 'Rotasi API key dijadwalkan 15 Agustus 2026.');

    $log = SystemActivityLog::query()
        ->where('category', 'api')
        ->latest()
        ->first();

    expect($log)->not->toBeNull()
        ->and($log?->subject)->toBe('client-area')
        ->and($log?->event)->toBe('api_request')
        ->and($log?->context['path'] ?? null)->toBe('/api/v1/client-area')
        ->and($log?->context['status_code'] ?? null)->toBe(200);
});

test('client area api rejects missing origin when allowed origins are configured', function () {
    config()->set('api-auth.key', 'test-api-key');

    ClientAreaSetting::query()->create([
        'api_enabled' => true,
        'allowed_origin_frontend' => 'https://frontend-prod.test',
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.15'])
        ->getJson('/api/v1/client-area', apiKeyHeaders())
        ->assertForbidden()
        ->assertJsonPath('message', 'Header Origin wajib dikirim untuk API ini.');
});

test('client area api rejects requests when allowed origin frontend is not configured', function () {
    config()->set('api-auth.key', 'test-api-key');

    ClientAreaSetting::query()->create([
        'api_enabled' => true,
        'allowed_origin_frontend' => null,
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.16'])
        ->getJson('/api/v1/client-area', apiKeyHeaders())
        ->assertForbidden()
        ->assertJsonPath('message', 'Allowed Origin Frontend belum dikonfigurasi.');
});

test('client area api allows trusted mobile app requests without origin header', function () {
    config()->set('api-auth.key', 'test-api-key');

    ClientAreaSetting::query()->create([
        'api_enabled' => true,
        'allowed_origin_frontend' => null,
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.17'])
        ->withHeaders([
            ...apiKeyHeaders(),
            'X-Client-Type' => 'mobile-app',
        ])
        ->getJson('/api/v1/client-area')
        ->assertSuccessful()
        ->assertJsonPath('data.dev', false)
        ->assertJsonPath('data.prod', false);
});

test('client area api returns 503 when public api is disabled', function () {
    config()->set('api-auth.key', 'test-api-key');

    ClientAreaSetting::query()->create([
        'api_enabled' => false,
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.12'])
        ->getJson('/api/v1/client-area', apiKeyHeaders())
        ->assertStatus(503)
        ->assertJsonPath('message', 'Public API sedang dinonaktifkan.');
});

test('client area api rejects disallowed origin', function () {
    config()->set('api-auth.key', 'test-api-key');

    ClientAreaSetting::query()->create([
        'api_enabled' => true,
        'allowed_origin_frontend' => 'https://frontend-prod.test',
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.13'])
        ->withHeaders([
        ...apiKeyHeaders(),
        'Origin' => 'https://evil.test',
    ])->getJson('/api/v1/client-area')
        ->assertForbidden()
        ->assertJsonPath('message', 'Origin frontend tidak diizinkan.');
});
