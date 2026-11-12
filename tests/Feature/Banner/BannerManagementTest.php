<?php

use App\Models\Banner;
use App\Models\User;
use App\Support\OptimizedImageStorage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

uses(LazilyRefreshDatabase::class);

test('banner menus isolate types and reject cross-type edits and deletes', function () {
    $regular = Banner::factory()->create(['type' => Banner::TYPE_REGULAR]);
    $mini = Banner::factory()->create(['type' => Banner::TYPE_MINI_MICRO]);
    $this->actingAs(User::factory()->create());

    $this->get(route('banner.index'))->assertSuccessful()->assertSee($regular->title)->assertDontSee($mini->title);
    $this->get(route('banner-mini-micro.index'))->assertSuccessful()->assertSee($mini->title)->assertDontSee($regular->title);
    $this->get(route('banner-mini-micro.create'))->assertSuccessful()->assertSee('Banner Mini Micro');
    $this->get(route('banner.edit', $mini))->assertNotFound();
    $this->get(route('banner-mini-micro.edit', $regular))->assertNotFound();
    $this->delete(route('banner-mini-micro.destroy', $regular))->assertNotFound();
    $this->assertModelExists($regular);
});

test('creating a mini micro banner assigns its type from the route', function () {
    Storage::fake('public');
    $this->app->instance(OptimizedImageStorage::class, new class extends OptimizedImageStorage
    {
        public function store(UploadedFile $file, string $directory = 'uploads/banner'): string
        {
            return 'uploads/banner/test.webp';
        }
    });

    $this->actingAs(User::factory()->create())
        ->post(route('banner-mini-micro.store'), [
            'title' => 'Banner Mini Micro',
            'type' => Banner::TYPE_REGULAR,
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('banner.jpg'),
        ])->assertRedirect(route('banner-mini-micro.index'));

    expect(Banner::query()->sole()->type)->toBe(Banner::TYPE_MINI_MICRO);
});

test('guests are redirected to login when accessing banner pages', function () {
    $banner = Banner::factory()->create();

    $this->get(route('banner.index'))->assertRedirect(route('login'));
    $this->get(route('banner.create'))->assertRedirect(route('login'));
    $this->get(route('banner.edit', $banner))->assertRedirect(route('login'));
});

test('authenticated users can access banner pages', function () {
    $user = User::factory()->create();
    $banner = Banner::factory()->create();

    $this->actingAs($user);

    $this->get(route('banner.index'))
        ->assertSuccessful()
        ->assertSee('Banner')
        ->assertSee('Tambah Banner');

    $this->get(route('banner.create'))
        ->assertSuccessful()
        ->assertSee('Banner Reguler')
        ->assertSee('Judul Banner');

    $this->get(route('banner.edit', $banner))
        ->assertSuccessful()
        ->assertSee($banner->title)
        ->assertSee('Update Image Banner');
});

test('authenticated users can create update and delete banners', function () {
    $user = User::factory()->create();
    Http::fake();

    Storage::fake('public');

    $this->app->instance(OptimizedImageStorage::class, new class extends OptimizedImageStorage
    {
        public function store(UploadedFile $file, string $directory = 'uploads/banner'): string
        {
            $path = trim($directory, '/').'/'.uniqid('banner-', true).'.webp';
            Storage::disk('public')->put($path, 'fake-image');

            return $path;
        }

        public function delete(?string $path): void
        {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
    });

    $this->actingAs($user)
        ->post(route('banner.store'), [
            'title' => 'Banner reguler',
            'sort_order' => 2,
            'is_active' => '1',
            'is_notification' => '1',
            'image' => UploadedFile::fake()->image('banner.jpg'),
        ])
        ->assertRedirect(route('banner.index'));

    $banner = Banner::query()->firstOrFail();

    expect($banner->sort_order)->toBe(2)
        ->and($banner->is_active)->toBeTrue()
        ->and($banner->is_notification)->toBeTrue()
        ->and($banner->image)->toEndWith('.webp');

    Storage::disk('public')->assertExists($banner->image);

    $this->actingAs($user)
        ->put(route('banner.update', $banner), [
            'title' => 'Banner reguler diperbarui',
            'sort_order' => 5,
            'is_active' => '0',
            'is_notification' => '0',
            'image' => UploadedFile::fake()->image('banner-update.jpg'),
        ])
        ->assertRedirect(route('banner.index'));

    $banner->refresh();

    expect($banner->sort_order)->toBe(5)
        ->and($banner->is_active)->toBeFalse()
        ->and($banner->is_notification)->toBeFalse()
        ->and($banner->image)->toEndWith('.webp');

    Storage::disk('public')->assertExists($banner->image);

    $imagePath = $banner->image;

    $this->actingAs($user)
        ->delete(route('banner.destroy', $banner))
        ->assertRedirect(route('banner.index'));

    $this->assertModelMissing($banner);
    Storage::disk('public')->assertMissing($imagePath);
});

test('route binding prefers slug over numeric id and falls back to id', function () {
    $mini = Banner::factory()->create(['type' => Banner::TYPE_MINI_MICRO, 'slug' => null]);
    $regular = Banner::factory()->create(['type' => Banner::TYPE_REGULAR, 'slug' => (string) $mini->getKey()]);
    $slugless = Banner::factory()->create(['type' => Banner::TYPE_REGULAR, 'slug' => null]);
    $this->actingAs(User::factory()->create());

    expect((new Banner)->resolveRouteBinding((string) $mini->getKey())->is($regular))->toBeTrue();
    expect((new Banner)->resolveRouteBinding((string) $slugless->getKey())->is($slugless))->toBeTrue();
    expect($regular->getRouteKey())->toBe($regular->getKey());

    $this->delete(route('banner.destroy', $slugless))->assertRedirect(route('banner.index'));
    $this->assertModelMissing($slugless);
    expect(Banner::generateSlug('123'))->toBe('banner-123');
});
