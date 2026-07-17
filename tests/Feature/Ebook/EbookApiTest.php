<?php

use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('ebook api returns ebook items with search support', function () {
    $category = EbookCategory::factory()->create([
        'name' => 'Trading Dasar',
    ]);
    $otherCategory = EbookCategory::factory()->create([
        'name' => 'Psikologi Trading',
    ]);

    $ebook = Ebook::factory()->create([
        'title' => 'Panduan Trading',
        'ebook_category_id' => $category->id,
    ]);
    Ebook::factory()->create([
        'title' => 'Panduan Lain',
        'ebook_category_id' => $otherCategory->id,
    ]);

    app(ApiJsonCacheService::class)->refreshEbook();

    $this->getJson('/api/v1/ebook?search=Dasar')
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.id', $ebook->id)
        ->assertJsonPath('data.0.kategori', 'Trading Dasar');

    $this->getJson('/api/v1/ebook?category='.$category->slug)
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.category.slug', $category->slug);
});

test('ebook api can show a single item by slug', function () {
    $ebook = Ebook::factory()->create();

    app(ApiJsonCacheService::class)->refreshEbook();

    $this->getJson('/api/v1/ebook/' . $ebook->slug)
        ->assertSuccessful()
        ->assertJsonPath('data.id', $ebook->id)
        ->assertJsonPath('data.file', $ebook->file);
});

test('ebook category api returns category list and ebooks by category', function () {
    $category = EbookCategory::factory()->create([
        'name' => 'Edukasi Trading',
    ]);
    $ebook = Ebook::factory()->create([
        'title' => 'Commodity Basic',
        'ebook_category_id' => $category->id,
    ]);
    Ebook::factory()->create([
        'title' => 'Commodity Advanced',
        'ebook_category_id' => $category->id,
    ]);

    app(ApiJsonCacheService::class)->refreshEbook();
    app(ApiJsonCacheService::class)->refreshEbookCategories();

    $this->getJson('/api/v1/ebook/categories')
        ->assertSuccessful()
        ->assertJsonPath('data.0.name', $category->name)
        ->assertJsonPath('data.0.ebooks_count', 2);

    $this->getJson('/api/v1/ebook/categories/'.$category->slug)
        ->assertSuccessful()
        ->assertJsonPath('category.slug', $category->slug)
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('data.0.id', $ebook->id);

    $this->getJson('/api/v1/ebook/categories/'.$category->slug.'/detail')
        ->assertSuccessful()
        ->assertJsonPath('data.slug', $category->slug)
        ->assertJsonPath('data.ebooks_count', 2);
});
