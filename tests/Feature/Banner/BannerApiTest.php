<?php

use App\Models\Banner;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('banner api only returns active banners ordered by sort order', function () {
    Banner::factory()->create([
        'sort_order' => 3,
        'is_active' => true,
    ]);
    $firstBanner = Banner::factory()->create([
        'sort_order' => 1,
        'is_active' => true,
    ]);
    Banner::factory()->create([
        'sort_order' => 0,
        'is_active' => false,
    ]);

    app(ApiJsonCacheService::class)->refreshBanner();

    $response = $this->getJson('/api/banner');

    $response->assertSuccessful()
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('data.0.id', $firstBanner->id)
        ->assertJsonPath('data.0.sort_order', 1);
});

test('banner api can show single active banner by id', function () {
    $banner = Banner::factory()->create([
        'is_active' => true,
    ]);

    app(ApiJsonCacheService::class)->refreshBanner();

    $this->getJson('/api/banner/'.$banner->id)
        ->assertSuccessful()
        ->assertJsonPath('data.id', $banner->id)
        ->assertJsonPath('data.image', $banner->image);
});
