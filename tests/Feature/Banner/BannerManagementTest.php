<?php

use App\Models\Banner;
use App\Models\User;
use App\Support\OptimizedImageStorage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

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
        ->assertSee('Tambah image banner')
        ->assertSee('Image banner');

    $this->get(route('banner.edit', $banner))
        ->assertSuccessful()
        ->assertSee('Banner #'.$banner->id)
        ->assertSee('Update image banner');
});

test('authenticated users can create update and delete banners', function () {
    $user = User::factory()->create();

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
            'sort_order' => 2,
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('banner.jpg'),
        ])
        ->assertRedirect(route('banner.index'));

    $banner = Banner::query()->firstOrFail();

    expect($banner->sort_order)->toBe(2)
        ->and($banner->is_active)->toBeTrue()
        ->and($banner->image)->toEndWith('.webp');

    Storage::disk('public')->assertExists($banner->image);

    $this->actingAs($user)
        ->put(route('banner.update', $banner), [
            'sort_order' => 5,
            'is_active' => '0',
            'image' => UploadedFile::fake()->image('banner-update.jpg'),
        ])
        ->assertRedirect(route('banner.index'));

    $banner->refresh();

    expect($banner->sort_order)->toBe(5)
        ->and($banner->is_active)->toBeFalse()
        ->and($banner->image)->toEndWith('.webp');

    Storage::disk('public')->assertExists($banner->image);

    $imagePath = $banner->image;

    $this->actingAs($user)
        ->delete(route('banner.destroy', $banner))
        ->assertRedirect(route('banner.index'));

    $this->assertModelMissing($banner);
    Storage::disk('public')->assertMissing($imagePath);
});
