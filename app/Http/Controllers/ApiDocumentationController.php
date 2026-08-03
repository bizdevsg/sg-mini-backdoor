<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiDocumentation\DownloadApiDocumentationRequest;
use App\Support\SystemActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ApiDocumentationController extends Controller
{
    public function __construct(
        private readonly SystemActivityLogger $systemActivityLogger,
    ) {
    }

    public function show(): RedirectResponse
    {
        return redirect()->route('api-documentation.section', [
            'section' => 'getting-started',
        ]);
    }

    public function section(string $section): View
    {
        return view('api-documentation.show', $this->sectionDocumentationData($section));
    }

    public function pdf(DownloadApiDocumentationRequest $request): View
    {
        $validated = $request->validated();
        $requestedAt = now('Asia/Jakarta');

        $this->systemActivityLogger->log(
            category: 'data',
            event: 'data_download',
            description: "Download dokumentasi API (PDF) untuk keperluan \"{$validated['purpose']}\", diberikan kepada {$validated['recipient']}.",
            subject: 'api-documentation',
            user: $request->user(),
            request: $request,
            context: [
                'purpose' => $validated['purpose'],
                'recipient' => $validated['recipient'],
            ],
        );

        return view('api-documentation.pdf', [
            ...$this->allDocumentationData(),
            'requestedPurpose' => $validated['purpose'],
            'requestedRecipient' => $validated['recipient'],
            'requestedByName' => $request->user()?->name ?? 'Unknown',
            'requestedAt' => $requestedAt,
        ]);
    }

    /**
     * @return array<string, array{key: string, label: string, short_label: string, partial: string, dot_class: string}>
     */
    private function sections(): array
    {
        return [
            'getting-started' => [
                'key' => 'getting-started',
                'label' => 'Getting Started',
                'short_label' => 'Getting Started',
                'partial' => 'api-documentation.partials.getting-started',
                'dot_class' => 'bg-emerald-400',
            ],
            'authentication' => [
                'key' => 'authentication',
                'label' => 'Authentication',
                'short_label' => 'Authentication',
                'partial' => 'api-documentation.partials.authentication',
                'dot_class' => 'bg-gold',
            ],
            'endpoints' => [
                'key' => 'endpoints',
                'label' => 'Endpoints',
                'short_label' => 'Endpoints',
                'partial' => 'api-documentation.partials.endpoints',
                'dot_class' => 'bg-blue-400',
            ],
            'query-params' => [
                'key' => 'query-params',
                'label' => 'Query Params',
                'short_label' => 'Query Params',
                'partial' => 'api-documentation.partials.query-params',
                'dot_class' => 'bg-purple-400',
            ],
            'examples' => [
                'key' => 'examples',
                'label' => 'cURL Examples',
                'short_label' => 'cURL Examples',
                'partial' => 'api-documentation.partials.examples',
                'dot_class' => 'bg-orange-400',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function sectionDocumentationData(string $section): array
    {
        $sections = $this->sections();
        abort_unless(array_key_exists($section, $sections), 404);

        return [
            ...$this->sharedDocumentationData(),
            'docSections' => array_values($sections),
            'currentDocSection' => $sections[$section],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function allDocumentationData(): array
    {
        $sections = $this->sections();

        return [
            ...$this->sharedDocumentationData(),
            'docSections' => array_values($sections),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function sharedDocumentationData(): array
    {
        $sections = $this->sections();

        $apiBaseUrl = url('/api/v1');
        $apiKeyHeader = (string) config('api-auth.header', 'X-API-Key');
        $apiKeyValue = (string) config('api-auth.key', '');
        $mobileClientHeader = 'X-Client-Type';
        $mobileClientValue = 'mobile-app';
        $webOriginExample = 'https://frontend-prod.test';

        $endpointGroups = [
            [
                'title' => 'System Settings',
                'description' => 'List API System Settings',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/client-area', 'notes' => 'Status client area dan Tawk.to. Web wajib kirim Origin yang terdaftar. Mobile app wajib kirim X-Client-Type: mobile-app.'],
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
                'label' => 'Banner List - Web Client',
                'command' => "curl --request GET \"{$apiBaseUrl}/banner\" \\\n  --header \"{$apiKeyHeader}: {$apiKeyValue}\" \\\n  --header \"Origin: {$webOriginExample}\"",
            ],
            [
                'label' => 'System Settings - Mobile App',
                'command' => "curl --request GET \"{$apiBaseUrl}/client-area\" \\\n  --header \"{$apiKeyHeader}: {$apiKeyValue}\" \\\n  --header \"X-Client-Type: mobile-app\"",
            ],
            [
                'label' => 'Privacy Policy',
                'command' => "curl --request GET \"{$apiBaseUrl}/privacy-policy\" \\\n  --header \"{$apiKeyHeader}: {$apiKeyValue}\" \\\n  --header \"Origin: {$webOriginExample}\"",
            ],
        ];

        return [
            'apiBaseUrl' => $apiBaseUrl,
            'apiKeyHeader' => $apiKeyHeader,
            'apiKeyValue' => $apiKeyValue,
            'mobileClientHeader' => $mobileClientHeader,
            'mobileClientValue' => $mobileClientValue,
            'webOriginExample' => $webOriginExample,
            'endpointGroups' => $endpointGroups,
            'requestExamples' => $requestExamples,
            'docSections' => array_values($sections),
        ];
    }
}
