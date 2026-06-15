<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produk\StoreProdukRequest;
use App\Http\Requests\Produk\UpdateProdukRequest;
use App\Models\Produk;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProdukController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $section = $this->resolveSection($request);
        $kategori = $this->categoryForSection($section);

        $produks = Produk::query()
            ->where('kategori', $kategori)
            ->when(
                $request->string('search')->isNotEmpty(),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $search = $request->string('search')->toString();

                    $builder
                        ->where('nama_produk', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $this->apiJsonCacheService->ensureProdukCache();

        return view('produk.index', [
            'produks' => $produks,
            'section' => $section,
            'sectionLabel' => $this->sectionLabel($section),
        ]);
    }

    public function create(Request $request): View
    {
        $section = $this->resolveSection($request);

        return view('produk.create', [
            'section' => $section,
            'sectionLabel' => $this->sectionLabel($section),
        ]);
    }

    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $section = $this->resolveSection($request);
        $validated = $request->safe()->except('image');

        try {
            $imagePath = $this->optimizedImageStorage->store($request->file('image'));
        } catch (\RuntimeException $exception) {
            throw ValidationException::withMessages([
                'image' => $exception->getMessage(),
            ]);
        }

        Produk::create([
            ...$validated,
            'image' => $imagePath,
            'kategori' => $this->categoryForSection($section),
        ]);

        $this->apiJsonCacheService->refreshProduk();

        return redirect()
            ->route('produk.index', ['section' => $section])
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    public function show(string $section, Produk $produk): View
    {
        $section = $this->resolveSectionValue($section);
        $this->ensureSectionMatchesProduk($produk, $section);

        return view('produk.show', [
            'produk' => $produk,
            'section' => $section,
            'sectionLabel' => $this->sectionLabel($section),
        ]);
    }

    public function edit(string $section, Produk $produk): View
    {
        $section = $this->resolveSectionValue($section);
        $this->ensureSectionMatchesProduk($produk, $section);

        return view('produk.edit', [
            'produk' => $produk,
            'section' => $section,
            'sectionLabel' => $this->sectionLabel($section),
        ]);
    }

    public function update(UpdateProdukRequest $request, string $section, Produk $produk): RedirectResponse
    {
        $section = $this->resolveSectionValue($section);
        $this->ensureSectionMatchesProduk($produk, $section);
        $validated = $request->safe()->except('image');

        $imagePath = $produk->image;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'));
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($produk->image);
        }

        $produk->update([
            ...$validated,
            'image' => $imagePath,
            'kategori' => $this->categoryForSection($section),
        ]);

        $this->apiJsonCacheService->refreshProduk();

        return redirect()
            ->route('produk.index', ['section' => $section])
            ->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $section, Produk $produk): RedirectResponse
    {
        $section = $this->resolveSectionValue($section);
        $this->ensureSectionMatchesProduk($produk, $section);

        $this->optimizedImageStorage->delete($produk->image);
        $produk->delete();

        $this->apiJsonCacheService->refreshProduk();

        return redirect()
            ->route('produk.index', ['section' => $section])
            ->with('status', 'Produk berhasil dihapus.');
    }

    private function resolveSection(Request $request): string
    {
        return $this->resolveSectionValue($request->route('section'));
    }

    private function resolveSectionValue(?string $section): string
    {
        return match ($section) {
            'spa' => 'spa',
            'jfx' => 'jfx',
            default => 'spa',
        };
    }

    private function sectionLabel(string $section): string
    {
        return match ($section) {
            'jfx' => 'JFX',
            default => 'SPA',
        };
    }

    private function categoryForSection(string $section): string
    {
        return $this->sectionLabel($section);
    }

    private function ensureSectionMatchesProduk(Produk $produk, string $section): void
    {
        abort_unless($produk->kategori === $this->categoryForSection($section), 404);
    }
}
