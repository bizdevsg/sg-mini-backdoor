<?php

use App\Models\User;
use App\Models\WakilPialang;
use App\Models\WakilPialangCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function wakilPialangCategory(): WakilPialangCategory
{
    return WakilPialangCategory::create([
        'slug' => 'kantor-pusat',
        'nama_kategori' => 'Kantor Pusat',
        'alamat_kantor_cabang' => 'Jakarta',
        'telp' => '021000000',
        'link_google_maps' => 'https://maps.google.com',
    ]);
}

test('admin can add multiple wakil pialang at once', function () {
    $user = User::factory()->create();
    $category = wakilPialangCategory();

    $this->actingAs($user)
        ->post(route('wakil-pialang.batch.store', $category), [
            'items' => [
                ['nama' => 'Andi Wijaya', 'no_identitas' => 'WPB-001', 'status' => 'aktif'],
                ['nama' => 'Budi Santoso', 'no_identitas' => 'WPB-002', 'status' => 'tidak_aktif'],
            ],
        ])
        ->assertRedirect(route('wakil-pialang.index', $category));

    expect(WakilPialang::query()->where('id_kategori', $category->id)->count())->toBe(2);
    $this->assertDatabaseHas('wakil_pialangs', ['nama' => 'Andi Wijaya', 'slug' => 'andi-wijaya', 'no_identitas' => 'WPB-001']);
    $this->assertDatabaseHas('wakil_pialangs', ['nama' => 'Budi Santoso', 'slug' => 'budi-santoso', 'no_identitas' => 'WPB-002']);
});

test('batch add requires unique identity numbers in the submitted items', function () {
    $user = User::factory()->create();
    $category = wakilPialangCategory();

    $this->actingAs($user)
        ->from(route('wakil-pialang.batch.create', $category))
        ->post(route('wakil-pialang.batch.store', $category), [
            'items' => [
                ['nama' => 'Andi Wijaya', 'no_identitas' => 'WPB-001', 'status' => 'aktif'],
                ['nama' => 'Budi Santoso', 'no_identitas' => 'WPB-001', 'status' => 'aktif'],
            ],
        ])
        ->assertInvalid(['items.0.no_identitas', 'items.1.no_identitas']);

    expect(WakilPialang::query()->count())->toBe(0);
});
