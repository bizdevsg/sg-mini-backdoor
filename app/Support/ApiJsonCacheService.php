<?php

namespace App\Support;

use App\Http\Resources\BannerResource;
use App\Http\Resources\CompanyProfileResource;
use App\Http\Resources\EbookResource;
use App\Http\Resources\EbookCategoryResource;
use App\Http\Resources\InformasiResource;
use App\Http\Resources\LegalitasResource;
use App\Http\Resources\PenghargaanResource;
use App\Http\Resources\ProdukResource;
use App\Models\Banner;
use App\Models\CompanyProfile;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\Informasi;
use App\Models\Legalitas;
use App\Models\Penghargaan;
use App\Models\Produk;
use Illuminate\Support\Facades\File;

class ApiJsonCacheService
{
    public function ensureBannerCache(): void
    {
        if ($this->cacheExists('banner')) {
            return;
        }

        $this->refreshBanner();
    }

    public function ensureProdukCache(): void
    {
        if ($this->cacheExists('produk-spa') && $this->cacheExists('produk-jfx')) {
            return;
        }

        $this->refreshProduk();
    }

    public function ensurePengumumanCache(): void
    {
        if ($this->cacheExists('pengumuman')) {
            return;
        }

        $this->refreshPengumuman();
    }

    public function ensureEbookCache(): void
    {
        if ($this->cacheExists('ebook')) {
            return;
        }

        $this->refreshEbook();
    }

    public function ensureEbookCategoryCache(): void
    {
        if ($this->cacheExists('ebook-categories')) {
            return;
        }

        $this->refreshEbookCategories();
    }

    public function ensurePenghargaanCache(): void
    {
        if ($this->cacheExists('penghargaan')) {
            return;
        }

        $this->refreshPenghargaan();
    }

    public function ensureLegalitasCache(): void
    {
        if ($this->cacheExists('legalitas')) {
            return;
        }

        $this->refreshLegalitas();
    }

    public function ensureCompanyProfileCache(): void
    {
        if ($this->cacheExists('company-profile')) {
            return;
        }

        $this->refreshCompanyProfile();
    }

    public function refreshProduk(): void
    {
        $spaItems = Produk::query()
            ->where('kategori', 'SPA')
            ->latest()
            ->get()
            ->map(fn (Produk $produk) => (new ProdukResource($produk))->resolve())
            ->all();

        $jfxItems = Produk::query()
            ->where('kategori', 'JFX')
            ->latest()
            ->get()
            ->map(fn (Produk $produk) => (new ProdukResource($produk))->resolve())
            ->all();

        $this->write('produk-spa', $spaItems);
        $this->write('produk-jfx', $jfxItems);
    }

    public function refreshBanner(): void
    {
        $items = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->latest('id')
            ->get()
            ->map(fn (Banner $banner) => (new BannerResource($banner))->resolve())
            ->all();

        $this->write('banner', $items);
    }

    public function refreshPengumuman(): void
    {
        $items = Informasi::query()
            ->latest()
            ->get()
            ->map(fn (Informasi $informasi) => (new InformasiResource($informasi))->resolve())
            ->all();

        $this->write('pengumuman', $items);
    }

    public function refreshEbook(): void
    {
        $items = Ebook::query()
            ->with('category')
            ->latest()
            ->get()
            ->map(fn (Ebook $ebook) => (new EbookResource($ebook))->resolve())
            ->all();

        $this->write('ebook', $items);
    }

    public function refreshEbookCategories(): void
    {
        $items = EbookCategory::query()
            ->withCount('ebooks')
            ->orderBy('name')
            ->get()
            ->map(fn (EbookCategory $category) => (new EbookCategoryResource($category))->resolve())
            ->all();

        $this->write('ebook-categories', $items);
    }

    public function refreshPenghargaan(): void
    {
        $items = Penghargaan::query()
            ->latest()
            ->get()
            ->map(fn (Penghargaan $penghargaan) => (new PenghargaanResource($penghargaan))->resolve())
            ->all();

        $this->write('penghargaan', $items);
    }

    public function refreshLegalitas(): void
    {
        $items = Legalitas::query()
            ->latest()
            ->get()
            ->map(fn (Legalitas $legalitas) => (new LegalitasResource($legalitas))->resolve())
            ->all();

        $this->write('legalitas', $items);
    }

    public function refreshCompanyProfile(): void
    {
        $profile = CompanyProfile::query()->latest('id')->first();

        $items = $profile
            ? [(new CompanyProfileResource($profile))->resolve()]
            : [];

        $this->write('company-profile', $items);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function produkItems(string $section): array
    {
        return array_map(
            fn (array $item) => $this->normalizeProdukItem($item),
            $this->readItems('produk-' . $section)
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function bannerItems(): array
    {
        return $this->readItems('banner');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function pengumumanItems(): array
    {
        return array_map(
            fn (array $item) => $this->normalizeInformasiItem($item),
            $this->readItems('pengumuman')
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function ebookItems(): array
    {
        return $this->readItems('ebook');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function ebookCategoryItems(): array
    {
        return $this->readItems('ebook-categories');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function penghargaanItems(): array
    {
        return $this->readItems('penghargaan');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function legalitasItems(): array
    {
        return $this->readItems('legalitas');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function companyProfileItem(): ?array
    {
        $items = $this->readItems('company-profile');

        if ($items === []) {
            return null;
        }

        return is_array($items[0] ?? null) ? $items[0] : null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<int, string>  $fields
     * @return array<int, array<string, mixed>>
     */
    public function search(array $items, string $search, array $fields): array
    {
        $search = mb_strtolower(trim($search));

        if ($search === '') {
            return $items;
        }

        return array_values(array_filter($items, function (array $item) use ($search, $fields): bool {
            foreach ($fields as $field) {
                $value = mb_strtolower((string) data_get($item, $field, ''));

                if ($value !== '' && str_contains($value, $search)) {
                    return true;
                }
            }

            return false;
        }));
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, mixed>
     */
    public function paginate(array $items, int $perPage, int $page, string $path, array $query = []): array
    {
        $total = count($items);
        $perPage = max(1, min($perPage, 100));
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $lastPage));
        $offset = ($page - 1) * $perPage;

        return [
            'data' => array_slice($items, $offset, $perPage),
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => $lastPage,
                'from' => $total > 0 ? $offset + 1 : null,
                'to' => $total > 0 ? min($offset + $perPage, $total) : null,
            ],
            'links' => [
                'path' => $path,
                'query' => $query,
            ],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, mixed>|null
     */
    public function findBySlug(array $items, string $slug): ?array
    {
        foreach ($items as $item) {
            if (($item['slug'] ?? null) === $slug) {
                return $item;
            }
        }

        return null;
    }

    private function cacheExists(string $key): bool
    {
        return File::exists($this->path($key));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function readItems(string $key): array
    {
        $path = $this->path($key);

        if (! File::exists($path)) {
            return [];
        }

        $payload = json_decode((string) File::get($path), true);

        if (! is_array($payload)) {
            return [];
        }

        return is_array($payload['items'] ?? null) ? $payload['items'] : [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function write(string $key, array $items): void
    {
        File::ensureDirectoryExists($this->directory());

        File::put(
            $this->path($key),
            json_encode([
                'generated_at' => now()->toIso8601String(),
                'items' => $items,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function directory(): string
    {
        return storage_path('app/api-json-cache');
    }

    private function path(string $key): string
    {
        return $this->directory() . DIRECTORY_SEPARATOR . $key . '.json';
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function normalizeProdukItem(array $item): array
    {
        if (array_key_exists('specs_html', $item)) {
            $item['specs'] = $item['specs_html'] ?: ($item['specs'] ?? '');
            unset($item['specs_html']);
        }

        return $item;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function normalizeInformasiItem(array $item): array
    {
        if (array_key_exists('content_html', $item)) {
            $item['content'] = $item['content_html'] ?: ($item['content'] ?? '');
            unset($item['content_html']);
        }

        return $item;
    }
}
