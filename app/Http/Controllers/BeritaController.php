<?php

namespace App\Http\Controllers;

use App\Http\Requests\Berita\StoreBeritaRequest;
use App\Http\Requests\Berita\UpdateBeritaRequest;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BeritaController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request, BeritaCategory $beritaCategory): View
    {
        $search = $request->string('search')->toString();

        $beritas = Berita::query()
            ->with('category')
            ->whereBelongsTo($beritaCategory, 'category')
            ->applySearch($search)
            ->orderForListing()
            ->paginate(20)
            ->withQueryString();

        return view('berita.index', [
            'beritaCategory' => $beritaCategory,
            'beritas' => $beritas,
        ]);
    }

    public function create(BeritaCategory $beritaCategory): View
    {
        return view('berita.create', [
            'beritaCategory' => $beritaCategory,
        ]);
    }

    public function store(StoreBeritaRequest $request, BeritaCategory $beritaCategory): RedirectResponse
    {
        $validated = $request->safe()->except('image');
        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/berita');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }
        }

        Berita::create(Berita::prepareForPersistence([
            ...$validated,
            'berita_category_id' => $beritaCategory->id,
            'image' => $imagePath,
        ]));

        $this->apiJsonCacheService->refreshBerita();
        $this->apiJsonCacheService->refreshBeritaCategories();

        return redirect()
            ->route('berita.index', $beritaCategory)
            ->with('status', 'Berita berhasil ditambahkan.');
    }

    public function show(BeritaCategory $beritaCategory, Berita $berita): View
    {
        $this->ensureCategoryMatchesBerita($beritaCategory, $berita);
        $berita->load('category');

        return view('berita.show', [
            'beritaCategory' => $beritaCategory,
            'berita' => $berita,
        ]);
    }

    public function edit(BeritaCategory $beritaCategory, Berita $berita): View
    {
        $this->ensureCategoryMatchesBerita($beritaCategory, $berita);
        $berita->load('category');

        return view('berita.edit', [
            'beritaCategory' => $beritaCategory,
            'berita' => $berita,
        ]);
    }

    public function update(UpdateBeritaRequest $request, BeritaCategory $beritaCategory, Berita $berita): RedirectResponse
    {
        $this->ensureCategoryMatchesBerita($beritaCategory, $berita);
        $validated = $request->safe()->except('image');
        $imagePath = $berita->image;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/berita');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($berita->image);
        }

        $berita->update(Berita::prepareForPersistence([
            ...$validated,
            'berita_category_id' => $beritaCategory->id,
            'image' => $imagePath,
        ]));

        $this->apiJsonCacheService->refreshBerita();
        $this->apiJsonCacheService->refreshBeritaCategories();

        return redirect()
            ->route('berita.index', $beritaCategory)
            ->with('status', 'Berita berhasil diperbarui.');
    }

    public function destroy(BeritaCategory $beritaCategory, Berita $berita): RedirectResponse
    {
        $this->ensureCategoryMatchesBerita($beritaCategory, $berita);
        $this->optimizedImageStorage->delete($berita->image);
        $berita->delete();

        $this->apiJsonCacheService->refreshBerita();
        $this->apiJsonCacheService->refreshBeritaCategories();

        return redirect()
            ->route('berita.index', $beritaCategory)
            ->with('status', 'Berita berhasil dihapus.');
    }

    private function ensureCategoryMatchesBerita(BeritaCategory $beritaCategory, Berita $berita): void
    {
        abort_unless($berita->berita_category_id === $beritaCategory->id, 404);
    }
}
