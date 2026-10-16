<?php

use App\Models\Produk;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('prime product api only returns prime products', function () {
    $prime = Produk::factory()->create([
        'nama_produk' => 'Prime Gold',
        'slug' => 'prime-gold',
        'kategori' => 'PRIME',
    ]);

    Produk::factory()->create([
        'nama_produk' => 'Bilateral Gold',
        'slug' => 'bilateral-gold',
        'kategori' => 'SPA',
    ]);

    app(ApiJsonCacheService::class)->refreshProduk();

    $this->getJson('/api/v1/produk/prime', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.id', $prime->id)
        ->assertJsonPath('data.0.kategori', 'PRIME')
        ->assertJsonMissing(['slug' => 'bilateral-gold']);
});

test('prime product api can show a product by slug', function () {
    $prime = Produk::factory()->create([
        'slug' => 'prime-platinum',
        'kategori' => 'PRIME',
        'deskripsi_produk' => 'Deskripsi Prime Platinum.',
        'deskripsi_produk_en' => 'Prime Platinum description.',
    ]);

    app(ApiJsonCacheService::class)->refreshProduk();

    $this->getJson('/api/v1/produk/prime/'.$prime->slug, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.id', $prime->id)
        ->assertJsonPath('data.deskripsi_produk_id', 'Deskripsi Prime Platinum.')
        ->assertJsonPath('data.deskripsi_produk_en', 'Prime Platinum description.')
        ->assertJsonPath('data.kategori', 'PRIME');
});

test('prime product api rejects products from another section', function () {
    $spa = Produk::factory()->create([
        'slug' => 'produk-bilateral',
        'kategori' => 'SPA',
    ]);

    app(ApiJsonCacheService::class)->refreshProduk();

    $this->getJson('/api/v1/produk/prime/'.$spa->slug, apiKeyHeaders())
        ->assertNotFound();
});

test('product api falls back to the Indonesian description for legacy records', function () {
    $prime = Produk::factory()->create([
        'slug' => 'prime-legacy',
        'kategori' => 'PRIME',
        'deskripsi_produk' => 'Deskripsi produk lama.',
        'deskripsi_produk_en' => null,
    ]);

    app(ApiJsonCacheService::class)->refreshProduk();

    $this->getJson('/api/v1/produk/prime/'.$prime->slug, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.deskripsi_produk', 'Deskripsi produk lama.')
        ->assertJsonPath('data.deskripsi_produk_id', 'Deskripsi produk lama.')
        ->assertJsonPath('data.deskripsi_produk_en', 'Deskripsi produk lama.');
});
