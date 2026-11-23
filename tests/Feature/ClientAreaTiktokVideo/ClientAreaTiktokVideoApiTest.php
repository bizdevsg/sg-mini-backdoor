<?php

use App\Models\ClientAreaTiktokVideo;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('tiktok video api only returns active videos ordered newest first', function () {
    $olderVideo = ClientAreaTiktokVideo::factory()->active()->create([
        'video_id' => 'AAAAAAAAAA1',
        'created_at' => now()->subDay(),
    ]);
    $newerVideo = ClientAreaTiktokVideo::factory()->active()->create([
        'video_id' => 'AAAAAAAAAA2',
        'file_path' => 'videos/contoh.mp4',
        'created_at' => now(),
    ]);
    ClientAreaTiktokVideo::factory()->create([
        'video_id' => 'AAAAAAAAAA3',
    ]);

    app(ApiJsonCacheService::class)->refreshClientAreaTiktokVideo();

    $response = $this->getJson('/api/v1/video-tiktok', apiKeyHeaders());

    $response->assertSuccessful()
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('data.0.id', $newerVideo->id)
        ->assertJsonPath('data.1.id', $olderVideo->id)
        ->assertJsonPath('data.0.embed_url', 'https://www.youtube.com/embed/'.$newerVideo->video_id)
        ->assertJsonPath('data.0.autoplay_embed_url', 'https://www.youtube.com/embed/'.$newerVideo->video_id.'?autoplay=1&mute=1&playsinline=1&rel=0')
        ->assertJsonPath('data.0.file_url', \Illuminate\Support\Facades\Storage::disk('public')->url('videos/contoh.mp4'))
        ->assertJsonPath('data.1.file_url', null);
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
