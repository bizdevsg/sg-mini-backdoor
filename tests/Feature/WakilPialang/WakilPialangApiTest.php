<?php

use App\Models\WakilPialang;
use App\Models\WakilPialangCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function makeWakilPialangCategory(string $nama): WakilPialangCategory
{
    return WakilPialangCategory::query()->create([
        'slug' => WakilPialangCategory::generateSlug($nama),
        'nama_kategori' => $nama,
        'alamat_kantor_cabang' => 'Jl. Jend. Sudirman No. 1',
        'telp' => '021-5551234',
        'link_google_maps' => 'https://maps.google.com/?q=sudirman',
    ]);
}

function makeWakilPialang(string $nama, string $noIdentitas, WakilPialangCategory $category, string $status): WakilPialang
{
    return WakilPialang::query()->create([
        'slug' => WakilPialang::generateSlug($nama),
        'nama' => $nama,
        'no_identitas' => $noIdentitas,
        'id_kategori' => $category->id,
        'status' => $status,
    ]);
}

function refreshWakilPialangApiCache(): void
{
    app(ApiJsonCacheService::class)->refreshWakilPialang();
    app(ApiJsonCacheService::class)->refreshWakilPialangCategories();
}

test('wakil pialang slug is unique per name', function () {
    $pusat = makeWakilPialangCategory('Pusat');

    makeWakilPialang('Andi Wijaya', 'WPB-001', $pusat, 'aktif');

    expect(WakilPialang::generateSlug('Andi Wijaya'))->toBe('andi-wijaya-2')
        ->and(WakilPialangCategory::generateSlug('Pusat'))->toBe('pusat-2');
});

test('wakil pialang api lists filters and shows items by slug', function () {
    $pusat = makeWakilPialangCategory('Pusat');
    $cabang = makeWakilPialangCategory('Cabang');

    $andi = makeWakilPialang('Andi', 'WPB-001', $pusat, 'aktif');
    makeWakilPialang('Budi', 'WPB-002', $pusat, 'tidak_aktif');
    makeWakilPialang('Citra', 'WPB-003', $cabang, 'aktif');

    refreshWakilPialangApiCache();

    $this->getJson('/api/v1/wakil-pialang-berjangka', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 3);

    $this->getJson('/api/v1/wakil-pialang-berjangka?status=aktif&category='.$pusat->slug, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.nama', 'Andi')
        ->assertJsonPath('data.0.slug', 'andi')
        ->assertJsonPath('data.0.status_label', 'Aktif')
        ->assertJsonPath('data.0.category.slug', 'pusat')
        ->assertJsonPath('data.0.category.telp', '021-5551234');

    $this->getJson('/api/v1/wakil-pialang-berjangka?search=WPB-003', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.nama', 'Citra');

    $this->getJson('/api/v1/wakil-pialang-berjangka/'.$andi->slug, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'andi')
        ->assertJsonPath('data.no_identitas', 'WPB-001');

    $this->getJson('/api/v1/wakil-pialang-berjangka/tidak-ada', apiKeyHeaders())
        ->assertNotFound();
});

test('wakil pialang category api returns categories with their members by slug', function () {
    $pusat = makeWakilPialangCategory('Pusat');
    makeWakilPialangCategory('Kosong');

    makeWakilPialang('Andi', 'WPB-001', $pusat, 'aktif');

    refreshWakilPialangApiCache();

    $this->getJson('/api/v1/wakil-pialang-berjangka/categories', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 20)
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('meta.last_page', 1)
        ->assertJsonPath('data.1.slug', 'pusat')
        ->assertJsonPath('data.1.wakil_pialangs_count', 1)
        ->assertJsonPath('data.1.link_google_maps', 'https://maps.google.com/?q=sudirman');

    $this->getJson('/api/v1/wakil-pialang-berjangka/categories?search=Pusat&per_page=1', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('meta.per_page', 1)
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.slug', 'pusat');

    $this->getJson('/api/v1/wakil-pialang-berjangka/categories/'.$pusat->slug, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('category.slug', 'pusat')
        ->assertJsonPath('data.0.nama', 'Andi');

    $this->getJson('/api/v1/wakil-pialang-berjangka/categories/'.$pusat->slug.'/detail', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.nama_kategori', 'Pusat')
        ->assertJsonCount(1, 'data.wakil_pialangs');

    $this->getJson('/api/v1/wakil-pialang-berjangka/categories/tidak-ada', apiKeyHeaders())
        ->assertNotFound();
});
