@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @php
        $statCards = [
            [
                'icon' => 'fa-solid fa-box-archive',
                'title' => 'Total Produk Multilateral',
                'value' => $produkJFXcount,
                'description' => 'Seluruh katalog produk multilateral.',
            ],
            [
                'icon' => 'fa-solid fa-box-archive',
                'title' => 'Total Produk Bilateral',
                'value' => $produkSPAcount,
                'description' => 'Seluruh katalog produk Bilateral.',
            ],
            [
                'icon' => 'fa-solid fa-globe',
                'title' => 'Total Banner',
                'value' => $bannerCount,
                'description' => 'Banner yang ditampilkan.',
            ],
            [
                'icon' => 'fa-solid fa-bullhorn',
                'title' => 'Pengumuman',
                'value' => $informasiCount,
                'description' => 'Materi informasi yang tersedia.',
            ],
            [
                'icon' => 'fa-solid fa-award',
                'title' => 'Penghargaan',
                'value' => $penghargaanCount,
                'description' => 'Dokumentasi penghargaan aktif.',
            ],
            [
                'icon' => 'fa-solid fa-users-gear',
                'title' => 'User Admin',
                'value' => $userCount,
                'description' => "{$superadminCount} superadmin dengan akses penuh.",
            ],
        ];

        $productActions = [
            [
                'href' => route('produk.index', ['section' => 'jfx']),
                'label' => 'Lihat Multilateral',
            ],
            [
                'href' => route('produk.index', ['section' => 'spa']),
                'label' => 'Lihat Bilateral',
            ],
        ];
    @endphp

    <section class="space-y-6">
        @include('components.organisms.stats-grid', ['stats' => $statCards])

        @include('components.organisms.recent-products-panel', [
            'products' => $recentProducts,
            'actions' => $productActions,
        ])
    </section>
@endsection
