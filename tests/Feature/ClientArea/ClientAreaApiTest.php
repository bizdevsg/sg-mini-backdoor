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
    ]);

    $this->getJson('/api/v1/client-area', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.dev', true)
        ->assertJsonPath('data.prod', false);

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
