<?php

use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing ebook pages', function () {
    $category = EbookCategory::factory()->create();
    $ebook = Ebook::factory()->create([
        'ebook_category_id' => $category->id,
    ]);

    $this->get(route('ebook.index', $category))->assertRedirect(route('login'));
    $this->get(route('ebook.create', $category))->assertRedirect(route('login'));
    $this->get(route('ebook.show', ['ebookCategory' => $category, 'ebook' => $ebook]))->assertRedirect(route('login'));
    $this->get(route('ebook.edit', ['ebookCategory' => $category, 'ebook' => $ebook]))->assertRedirect(route('login'));
    $this->get(route('ebook-categories.index'))->assertRedirect(route('login'));
});

test('authenticated users can browse ebook pages', function () {
    $user = User::factory()->create();
    $category = EbookCategory::factory()->create([
        'name' => 'Analisis Pasar',
    ]);
    $ebook = Ebook::factory()->create([
        'title' => 'Panduan Ebook',
        'ebook_category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->get(route('ebook-categories.index'))
        ->assertSuccessful()
        ->assertSee($category->name);

    $this->get(route('ebook.index', $category))
        ->assertSuccessful()
        ->assertSee($ebook->title)
        ->assertSee($category->name)
        ->assertSee('Tambah Ebook');

    $this->get(route('ebook.show', ['ebookCategory' => $category, 'ebook' => $ebook]))
        ->assertSuccessful()
        ->assertSee($ebook->title);

    $this->get(route('ebook.edit', ['ebookCategory' => $category, 'ebook' => $ebook]))
        ->assertSuccessful()
        ->assertSee('Update Ebook');
});

test('authenticated users can create and update ebooks', function () {
    $user = User::factory()->create();
    $category = EbookCategory::factory()->create([
        'name' => 'Trading Dasar',
    ]);

    Storage::fake('public');
    $this->actingAs($user);

    $this->post(route('ebook.store', $category), [
        'title' => 'Panduan Investasi',
        'description' => 'Deskripsi ebook investasi.',
        'file' => UploadedFile::fake()->create('ebook.pdf', 512, 'application/pdf'),
    ])->assertRedirect(route('ebook.index', $category));

    $ebook = Ebook::query()->firstOrFail();

    expect($ebook->title)->toBe('Panduan Investasi')
        ->and($ebook->ebook_category_id)->toBe($category->id)
        ->and($ebook->file)->toEndWith('.pdf');

    Storage::disk('public')->assertExists($ebook->file);

    $this->put(route('ebook.update', ['ebookCategory' => $category, 'ebook' => $ebook]), [
        'title' => 'Panduan Investasi Lanjutan',
        'description' => 'Deskripsi ebook lanjutan.',
        'file' => UploadedFile::fake()->create('ebook-lanjutan.pdf', 256, 'application/pdf'),
    ])->assertRedirect(route('ebook.index', $category));

    $ebook->refresh();

    expect($ebook->title)->toBe('Panduan Investasi Lanjutan')
        ->and($ebook->ebook_category_id)->toBe($category->id)
        ->and($ebook->file)->toEndWith('.pdf');

    Storage::disk('public')->assertExists($ebook->file);
});

test('authenticated users can manage ebook categories', function () {
    $user = User::factory()->create();
    $category = EbookCategory::factory()->create([
        'name' => 'Edukasi',
    ]);

    $this->actingAs($user)
        ->get(route('ebook-categories.index'))
        ->assertSuccessful()
        ->assertSee($category->name);

    $this->post(route('ebook-categories.store'), [
        'name' => 'Strategi Trading',
    ])->assertRedirect(route('ebook-categories.index'));

    $storedCategory = EbookCategory::query()
        ->where('name', 'Strategi Trading')
        ->firstOrFail();

    $this->put(route('ebook-categories.update', $storedCategory), [
        'name' => 'Strategi Trading Lanjutan',
    ])->assertRedirect(route('ebook-categories.index'));

    $storedCategory->refresh();

    expect($storedCategory->name)->toBe('Strategi Trading Lanjutan');
});
