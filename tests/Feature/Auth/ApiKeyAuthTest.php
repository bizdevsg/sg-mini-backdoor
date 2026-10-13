<?php

use App\Models\Banner;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('api rejects requests without api key', function () {
    $this->getJson('/api/v1/banner')
        ->assertForbidden()
        ->assertJsonPath('message', 'API key tidak valid.');
});

test('api accepts requests with valid api key header', function () {
    $banner = Banner::factory()->create([
        'is_active' => true,
    ]);

    app(ApiJsonCacheService::class)->refreshBanner();

    $this->getJson('/api/v1/banner', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.0.id', $banner->id);
});

test('api rejects requests with an invalid api key', function () {
    config()->set('api-auth.key_hash', hash('sha256', 'valid-api-key'));

    $this->getJson('/api/v1/banner', [
        'X-API-Key' => 'invalid-api-key',
    ])->assertForbidden()
        ->assertJsonPath('message', 'API key tidak valid.');
});

test('api rejects requests when api key hash is malformed', function () {
    config()->set('api-auth.key_hash', 'not-a-sha256-hash');

    $this->getJson('/api/v1/banner', [
        'X-API-Key' => 'any-api-key',
    ])->assertForbidden()
        ->assertJsonPath('message', 'API key belum dikonfigurasi.');
});
