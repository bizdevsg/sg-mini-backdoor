<?php

use App\Models\Informasi;
use App\Models\Penghargaan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('dashboard displays counts from database modules', function () {
    $user = User::factory()->superadmin()->create();

    User::factory()->count(2)->create();
    Produk::factory()->create([
        'nama_produk' => 'Produk SPA Unggulan',
        'kategori' => 'SPA',
    ]);
    Produk::factory()->create([
        'nama_produk' => 'Produk JFX Unggulan',
        'kategori' => 'JFX',
    ]);
    Informasi::factory()->create([
        'title' => 'Pengumuman Penting',
    ]);
    Penghargaan::factory()->create([
        'title' => 'Penghargaan Nasional',
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('Total User')
        ->assertSee('Total Produk')
        ->assertSee('Total Pengumuman')
        ->assertSee('Total Penghargaan')
        ->assertSee('2 Admin / 1 Superadmin')
        ->assertSee('1 SPA / 1 JFX')
        ->assertSee('Konten berita dan pengumuman aktif')
        ->assertSee('Dokumentasi penghargaan perusahaan');
});

test('dashboard recent activity uses database records', function () {
    $user = User::factory()->superadmin()->create([
        'name' => 'Supervisor Utama',
    ]);
    $produk = Produk::factory()->create([
        'nama_produk' => 'Produk Database',
        'kategori' => 'SPA',
    ]);
    $informasi = Informasi::factory()->create([
        'title' => 'Informasi Database',
    ]);
    $penghargaan = Penghargaan::factory()->create([
        'title' => 'Penghargaan Database',
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee($produk->nama_produk)
        ->assertSee($informasi->title)
        ->assertSee($penghargaan->title)
        ->assertSee('Supervisor Utama');
});
