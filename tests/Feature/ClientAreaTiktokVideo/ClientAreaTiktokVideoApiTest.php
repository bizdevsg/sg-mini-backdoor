<?php

use App\Models\ClientAreaTiktokVideo;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('tiktok video api only returns active videos ordered newest first', function () {
    $olderVideo = ClientAreaTiktokVideo::factory()->active()->create([
        'video_id' => '7700863615682858241',
        'created_at' => now()->subDay(),
    ]);
    $newerVideo = ClientAreaTiktokVideo::factory()->active()->create([
        'video_id' => '7700863615682858242',
        'created_at' => now(),
    ]);
    ClientAreaTiktokVideo::factory()->create([
        'video_id' => '7700863615682858243',
    ]);

    app(ApiJsonCacheService::class)->refreshClientAreaTiktokVideo();

    $response = $this->getJson('/api/v1/video-tiktok', apiKeyHeaders());

    $response->assertSuccessful()
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('data.0.id', $newerVideo->id)
        ->assertJsonPath('data.1.id', $olderVideo->id)
        ->assertJsonPath('data.0.embed_url', 'https://www.tiktok.com/player/v1/'.$newerVideo->video_id);
});

test('tiktok video api can search active videos', function () {
    ClientAreaTiktokVideo::factory()->active()->create([
        'title' => 'Analisis Harga Emas',
    ]);
    ClientAreaTiktokVideo::factory()->active()->create([
        'title' => 'Edukasi Trading',
    ]);

    app(ApiJsonCacheService::class)->refreshClientAreaTiktokVideo();

    $this->getJson('/api/v1/video-tiktok?search=emas', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.title', 'Analisis Harga Emas');
});

test('tiktok video api requires a valid api key', function () {
    $this->getJson('/api/v1/video-tiktok')
        ->assertForbidden();
});
