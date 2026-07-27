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
