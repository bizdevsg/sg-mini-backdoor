<?php

use App\Support\OptimizedImageStorage;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing product pages', function () {
    $produk = Produk::factory()->create();

    $this->get(route('produk.index', ['section' => 'spa']))->assertRedirect(route('login'));
    $this->get(route('produk.create', ['section' => 'spa']))->assertRedirect(route('login'));
    $this->get(route('produk.show', ['section' => 'spa', 'produk' => $produk]))->assertRedirect(route('login'));
    $this->get(route('produk.edit', ['section' => 'spa', 'produk' => $produk]))->assertRedirect(route('login'));
});

test('authenticated users can view product pages', function () {
    $user = User::factory()->create();
    $produk = Produk::factory()->create();

    $this->actingAs($user);

    $this->get(route('produk.index', ['section' => 'spa']))->assertSuccessful()->assertSee('Produk SPA');
    $this->get(route('produk.create', ['section' => 'spa']))->assertSuccessful()->assertSee('Tambah produk spa');
    $this->get(route('produk.show', ['section' => 'spa', 'produk' => $produk]))->assertSuccessful()->assertSee($produk->nama_produk);
    $this->get(route('produk.edit', ['section' => 'spa', 'produk' => $produk]))->assertSuccessful()->assertSee('Edit');
});

test('authenticated users can create and update products', function () {
    $user = User::factory()->create();

    Storage::fake('public');

    $this->app->instance(OptimizedImageStorage::class, new class extends OptimizedImageStorage
    {
        public function store(UploadedFile $file, string $directory = 'produk-images'): string
        {
            $path = trim($directory, '/').'/'.uniqid('produk-', true).'.webp';
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

    $this->actingAs($user);

    $this->post(route('produk.store', ['section' => 'spa']), [
        'nama_produk' => 'Produk Demo',
        'deskripsi_produk' => 'Deskripsi produk demo.',
        'specs' => 'Spesifikasi produk demo.',
        'image' => UploadedFile::fake()->image('demo.jpg'),
        'kategori' => 'SPA',
    ])->assertRedirect();

    $produk = Produk::query()->firstOrFail();

    $this->assertModelExists($produk);

    expect($produk->nama_produk)->toBe('Produk Demo')
        ->and($produk->slug)->toBe('produk-demo')
        ->and($produk->image)->toEndWith('.webp')
        ->and($produk->kategori)->toBe('SPA');

    Storage::disk('public')->assertExists($produk->image);

    $this->put(route('produk.update', ['section' => 'spa', 'produk' => $produk]), [
        'nama_produk' => 'Produk Demo Update',
        'deskripsi_produk' => 'Deskripsi produk demo update.',
        'specs' => 'Spesifikasi produk demo update.',
        'image' => UploadedFile::fake()->image('demo-update.png'),
        'kategori' => 'SPA',
    ])->assertRedirect(route('produk.show', ['section' => 'spa', 'produk' => $produk]));

    $produk->refresh();

    expect($produk->nama_produk)->toBe('Produk Demo Update')
        ->and($produk->slug)->toBe('produk-demo-update')
        ->and($produk->image)->toEndWith('.webp')
        ->and($produk->kategori)->toBe('SPA');

    Storage::disk('public')->assertExists($produk->image);
});
