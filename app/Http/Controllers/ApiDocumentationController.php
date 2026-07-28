<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ApiDocumentationController extends Controller
{
    public function show(): View
    {
        $apiBaseUrl = url('/api/v1');
        $apiKeyHeader = (string) config('api-auth.header', 'X-API-Key');
        $apiKeyValue = (string) config('api-auth.key', '');

        $endpointGroups = [
            [
                'title' => 'System Settings',
                'description' => 'List API System Settings',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/client-area', 'notes' => 'Status client area dan Tawk.to untuk development dan production.'],
                ],
            ],
            [
                'title' => 'Banner',
                'description' => 'List API Banner',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/banner', 'notes' => 'List banner aktif.'],
                    ['method' => 'GET', 'path' => '/banner/{slug}', 'notes' => 'Detail banner.'],
                ],
            ],
            [
                'title' => 'Produk',
                'description' => 'List API Produk',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/produk/spa', 'notes' => 'List produk bilateral SPA.'],
                    ['method' => 'GET', 'path' => '/produk/spa/{slug}', 'notes' => 'Detail produk SPA.'],
                    ['method' => 'GET', 'path' => '/produk/jfx', 'notes' => 'List produk multilateral JFX.'],
                    ['method' => 'GET', 'path' => '/produk/jfx/{slug}', 'notes' => 'Detail produk JFX.'],
                ],
            ],
            [
                'title' => 'Pengumuman',
                'description' => 'List API Pengumuman',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/pengumuman', 'notes' => 'List pengumuman.'],
                    ['method' => 'GET', 'path' => '/pengumuman/{slug}', 'notes' => 'Detail pengumuman.'],
                ],
            ],
            [
                'title' => 'Ebook',
                'description' => 'List API Ebook',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/ebook', 'notes' => 'List ebook, dukung `search`, `category`, dan pagination.'],
                    ['method' => 'GET', 'path' => '/ebook/{slug}', 'notes' => 'Detail ebook.'],
                    ['method' => 'GET', 'path' => '/ebook/categories', 'notes' => 'List kategori ebook.'],
                    ['method' => 'GET', 'path' => '/ebook/categories/{slug}', 'notes' => 'Ebook berdasarkan kategori.'],
                    ['method' => 'GET', 'path' => '/ebook/categories/{slug}/detail', 'notes' => 'Detail kategori ebook.'],
                ],
            ],
            [
                'title' => 'Signal',
                'description' => 'List API Signal',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/signal', 'notes' => 'List signal, dukung `search` dan pagination.'],
                    ['method' => 'GET', 'path' => '/signal/{slug}', 'notes' => 'Detail signal.'],
                    ['method' => 'GET', 'path' => '/signal/categories', 'notes' => 'List kategori signal.'],
                    ['method' => 'GET', 'path' => '/signal/categories/{slug}', 'notes' => 'Signal berdasarkan kategori.'],
                    ['method' => 'GET', 'path' => '/signal/categories/{slug}/detail', 'notes' => 'Detail kategori signal.'],
                ],
            ],
            [
                'title' => 'Berita',
                'description' => 'List API Berita',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/berita', 'notes' => 'List berita, dukung `search` dan pagination.'],
                    ['method' => 'GET', 'path' => '/berita/{slug}', 'notes' => 'Detail berita.'],
                    ['method' => 'GET', 'path' => '/berita/categories', 'notes' => 'List kategori berita.'],
                    ['method' => 'GET', 'path' => '/berita/categories/{slug}', 'notes' => 'Berita berdasarkan kategori.'],
                    ['method' => 'GET', 'path' => '/berita/categories/{slug}/detail', 'notes' => 'Detail kategori berita.'],
                ],
            ],
            [
                'title' => 'Penghargaan',
                'description' => 'List API Penghargaan',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/penghargaan', 'notes' => 'List penghargaan.'],
                    ['method' => 'GET', 'path' => '/penghargaan/{slug}', 'notes' => 'Detail penghargaan.'],
                ],
            ],
            [
                'title' => 'Legalitas',
                'description' => 'List API Legalitas',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/legalitas', 'notes' => 'Daftar legalitas.'],
                    ['method' => 'GET', 'path' => '/legalitas/{slug}', 'notes' => 'Detail legalitas.'],
                ],
            ],
            [
                'title' => 'Profil Perusahaan',
                'description' => 'List API Profil Perusahaan',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/company-profile', 'notes' => 'Profil perusahaan lengkap.'],
                ],
            ],
            [
                'title' => 'Syarat dan Ketentuan',
                'description' => 'List API Syarat dan Ketentuan',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/terms-and-conditions', 'notes' => 'Syarat dan ketentuan.'],
                ],
            ],
            [
                'title' => 'Kebijakan Privasi',
                'description' => 'List API Kebijakan Privasi',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/privacy-policy', 'notes' => 'Kebijakan privasi.'],
                ],
            ],
        ];

        $requestExamples = [
            [
                'label' => 'Banner List',
                'command' => "curl --request GET \"{$apiBaseUrl}/banner\" \\\n  --header \"{$apiKeyHeader}: {$apiKeyValue}\"",
            ],
            [
                'label' => 'System Settings Status',
                'command' => "curl --request GET \"{$apiBaseUrl}/client-area\" \\\n  --header \"{$apiKeyHeader}: {$apiKeyValue}\"",
            ],
            [
                'label' => 'Privacy Policy',
                'command' => "curl --request GET \"{$apiBaseUrl}/privacy-policy\" \\\n  --header \"{$apiKeyHeader}: {$apiKeyValue}\"",
            ],
        ];

        return view('api-documentation.show', compact(
            'apiBaseUrl',
            'apiKeyHeader',
            'apiKeyValue',
            'endpointGroups',
            'requestExamples',
        ));
    }
}
