<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeritaCategory\StoreBeritaCategoryRequest;
use App\Http\Requests\BeritaCategory\UpdateBeritaCategoryRequest;
use App\Models\BeritaCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BeritaCategoryController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = BeritaCategory::query()
            ->withCount('beritas')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('berita-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('berita-categories.create');
    }

    public function store(StoreBeritaCategoryRequest $request): RedirectResponse
    {
        BeritaCategory::create($request->validated());

        $this->apiJsonCacheService->refreshBeritaCategories();

        return redirect()
            ->route('berita-categories.index')
            ->with('status', 'Kategori berita berhasil ditambahkan.');
    }

    public function edit(BeritaCategory $beritaCategory): View
    {
        return view('berita-categories.edit', [
            'beritaCategory' => $beritaCategory,
        ]);
    }

    public function update(UpdateBeritaCategoryRequest $request, BeritaCategory $beritaCategory): RedirectResponse
    {
        $beritaCategory->update($request->validated());

        $this->apiJsonCacheService->refreshBeritaCategories();
        $this->apiJsonCacheService->refreshBerita();

        return redirect()
            ->route('berita-categories.index')
            ->with('status', 'Kategori berita berhasil diperbarui.');
    }

    public function destroy(BeritaCategory $beritaCategory): RedirectResponse
    {
        if ($beritaCategory->beritas()->exists()) {
            return redirect()
                ->route('berita-categories.index')
                ->with('status', 'Kategori berita tidak bisa dihapus karena masih dipakai berita.');
        }

        $beritaCategory->delete();

        $this->apiJsonCacheService->refreshBeritaCategories();

        return redirect()
            ->route('berita-categories.index')
            ->with('status', 'Kategori berita berhasil dihapus.');
    }
}
