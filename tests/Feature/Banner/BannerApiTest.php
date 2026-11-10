<?php

use App\Models\Banner;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders(['X-Client-Type' => 'mobile-app']);
});

test('banner api only returns active banners ordered by sort order', function () {
    Banner::factory()->create([
        'type' => Banner::TYPE_MINI_MICRO,
        'sort_order' => 3,
        'is_active' => true,
    ]);
    $firstBanner = Banner::factory()->create([
        'type' => Banner::TYPE_MINI_MICRO,
        'sort_order' => 1,
        'is_active' => true,
        'is_notification' => true,
    ]);
    Banner::factory()->create([
        'type' => Banner::TYPE_MINI_MICRO,
        'sort_order' => 0,
        'is_active' => false,
    ]);

    app(ApiJsonCacheService::class)->refreshBanner();

    $response = $this->getJson('/api/v1/banner', apiKeyHeaders());

    $response->assertSuccessful()
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('data.0.id', $firstBanner->id)
        ->assertJsonPath('data.0.is_notification', true)
        ->assertJsonPath('data.0.sort_order', 1);
});

test('banner api can show single active banner by id', function () {
    $banner = Banner::factory()->create([
        'type' => Banner::TYPE_MINI_MICRO,
        'is_active' => true,
    ]);

    app(ApiJsonCacheService::class)->refreshBanner();

    $this->getJson('/api/v1/banner/'.$banner->slug, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.id', $banner->id)
        ->assertJsonPath('data.image', $banner->image);
});

test('regular and mini micro banner APIs isolate lists and details', function () {
    $regular = Banner::factory()->create(['type' => Banner::TYPE_REGULAR, 'is_active' => true]);
    $mini = Banner::factory()->create(['type' => Banner::TYPE_MINI_MICRO, 'is_active' => true]);
    Banner::factory()->create(['type' => Banner::TYPE_MINI_MICRO, 'is_active' => false]);

    app(ApiJsonCacheService::class)->refreshBanner();

    $this->getJson('/api/v1/banner-reguler', apiKeyHeaders())
        ->assertSuccessful()->assertJsonPath('meta.total', 1)->assertJsonPath('data.0.id', $regular->id);
    $this->getJson('/api/v1/banner', apiKeyHeaders())
        ->assertSuccessful()->assertJsonPath('meta.total', 1)->assertJsonPath('data.0.id', $mini->id);
    $this->getJson('/api/v1/banner-mini-micro', apiKeyHeaders())
        ->assertSuccessful()->assertJsonPath('meta.total', 1)->assertJsonPath('data.0.id', $mini->id);
    $this->getJson('/api/v1/banner-reguler/'.$mini->slug, apiKeyHeaders())->assertNotFound();
    $this->getJson('/api/v1/banner/'.$regular->slug, apiKeyHeaders())->assertNotFound();
    $this->getJson('/api/v1/banner-mini-micro/'.$regular->slug, apiKeyHeaders())->assertNotFound();
    $this->getJson('/api/v1/banner-mini-micro/'.$mini->slug, apiKeyHeaders())
        ->assertSuccessful()->assertJsonPath('data.id', $mini->id);
    $this->getJson('/api/v1/banner/'.$mini->slug, apiKeyHeaders())
        ->assertSuccessful()->assertJsonPath('data.id', $mini->id);
    $this->getJson('/api/v1/banner-reguler/'.$regular->slug, apiKeyHeaders())
        ->assertSuccessful()->assertJsonPath('data.id', $regular->id);
});

test('the follow-up migration moves existing regular banners to mini micro', function () {
    $banner = Banner::factory()->create(['type' => Banner::TYPE_REGULAR]);
    $migration = require database_path('migrations/2026_11_10_000002_move_existing_banners_to_mini_micro.php');

    $migration->up();

    expect($banner->refresh()->type)->toBe(Banner::TYPE_MINI_MICRO);
});
