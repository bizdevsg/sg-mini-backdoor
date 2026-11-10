<?php

use App\Enums\PushNotificationStatus;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Models\PushNotification;
use App\Models\User;
use App\Support\OptimizedImageStorage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    config([
        'queue.default' => 'database',
        'services.mini_micro.base_url' => 'https://mini-micro.test',
        'services.mini_micro.api_key' => 'test-key',
        'services.mini_micro.dry_run' => true,
    ]);
    Http::preventStrayRequests();
});

test('a regular admin creates a berita and sends a dry run without a queue worker', function () {
    Http::fake(['mini-micro.test/*' => Http::response(['headers' => ['statusCode' => 200]])]);
    $category = BeritaCategory::query()->create(['name' => 'Market', 'slug' => 'market']);
    $user = User::factory()->create();
    $data = [
        'author' => 'Editor', 'source' => 'Redaksi', 'title_id' => 'Berita baru',
        'title_en' => 'News', 'content_id' => '<p>Isi berita</p>', 'content_en' => 'Content',
        'is_notification' => '1',
    ];

    $this->actingAs($user)->post(route('berita.store', $category), $data)
        ->assertSessionHas('status', fn ($message) => str_contains($message, 'Dry run'));

    $berita = Berita::query()->sole();
    $notification = PushNotification::query()->sole();
    expect($notification->status)->toBe(PushNotificationStatus::DryRun)
        ->and($notification->user_id)->toBe($user->id);
    Http::assertSent(fn (Request $request) => $request['topic'] === 'Announcement'
        && $request['news_id'] === $berita->id && str_contains($request->url(), 'logOnly=true'));

    $this->put(route('berita.update', [$category, $berita]), $data)->assertSessionHasNoErrors();
    Http::assertSentCount(1);
});

test('enabling banner notification sends once and falls back to the title when terms are empty', function () {
    Http::fake(['mini-micro.test/*' => Http::response(['headers' => ['statusCode' => 200]])]);
    $banner = Banner::factory()->create(['is_notification' => false, 'terms_and_conditions' => null]);
    $user = User::factory()->create();
    $data = ['title' => $banner->title, 'is_active' => '1', 'is_notification' => '1'];

    $this->actingAs($user)->put(route('banner.update', $banner), $data)->assertSessionHasNoErrors();
    Http::assertSent(fn (Request $request) => $request['topic'] === 'Promotion'
        && $request['promotion_id'] === $banner->id && $request['description'] === $request['title']);

    $this->put(route('banner.update', $banner->refresh()), $data)->assertSessionHasNoErrors();
    Http::assertSentCount(1);

    $this->put(route('banner.update', $banner->refresh()), [...$data, 'is_notification' => '0'])->assertSessionHasNoErrors();
    $this->put(route('banner.update', $banner->refresh()), $data)->assertSessionHasNoErrors();
    Http::assertSentCount(2);
});

test('live mode sends an automatic broadcast without logOnly', function () {
    config(['services.mini_micro.dry_run' => false]);
    Http::fake(['mini-micro.test/*' => Http::response(['headers' => ['statusCode' => 200]])]);
    $banner = Banner::factory()->create(['is_notification' => false]);

    $this->actingAs(User::factory()->create())->put(route('banner.update', $banner), [
        'title' => $banner->title, 'is_active' => '1', 'is_notification' => '1',
    ])->assertSessionHasNoErrors();

    Http::assertSent(fn (Request $request) => ! str_contains($request->url(), 'logOnly') && $request['broadcast'] === true);
    expect(PushNotification::query()->sole()->status)->toBe(PushNotificationStatus::Sent);
});

test('a failed automatic notification does not undo banner creation', function () {
    Http::fake(['mini-micro.test/*' => Http::response(['headers' => ['statusCode' => 404, 'message' => 'Not Found']], 200)]);
    Storage::fake('public');
    $this->app->instance(OptimizedImageStorage::class, new class extends OptimizedImageStorage
    {
        public function store(UploadedFile $file, string $directory = 'uploads/banner'): string
        {
            return 'uploads/banner/test.webp';
        }
    });

    $this->actingAs(User::factory()->create())->post(route('banner.store'), [
        'title' => 'Banner baru', 'is_notification' => '1', 'image' => UploadedFile::fake()->image('banner.jpg'),
    ])->assertSessionHas('status_type', 'error')->assertSessionHas('status', fn ($message) => str_contains($message, '404'));

    expect(Banner::query()->count())->toBe(1)
        ->and(PushNotification::query()->sole()->status)->toBe(PushNotificationStatus::Failed);
});
