<?php

namespace App\Http\Controllers;

use App\Http\Requests\EbookCategory\StoreEbookCategoryRequest;
use App\Http\Requests\EbookCategory\UpdateEbookCategoryRequest;
use App\Models\EbookCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EbookCategoryController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = EbookCategory::query()
            ->withCount('ebooks')
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

        return view('ebook-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('ebook-categories.create');
    }

    public function store(StoreEbookCategoryRequest $request): RedirectResponse
    {
        EbookCategory::create($request->validated());

        $this->apiJsonCacheService->refreshEbookCategories();

        return redirect()
            ->route('ebook-categories.index')
            ->with('status', 'Kategori ebook berhasil ditambahkan.');
    }

    public function edit(EbookCategory $ebookCategory): View
    {
        return view('ebook-categories.edit', [
            'ebookCategory' => $ebookCategory,
        ]);
    }

    public function update(UpdateEbookCategoryRequest $request, EbookCategory $ebookCategory): RedirectResponse
    {
        $ebookCategory->update($request->validated());

        $this->apiJsonCacheService->refreshEbookCategories();
        $this->apiJsonCacheService->refreshEbook();

        return redirect()
            ->route('ebook-categories.index')
            ->with('status', 'Kategori ebook berhasil diperbarui.');
    }

    public function destroy(EbookCategory $ebookCategory): RedirectResponse
    {
        if ($ebookCategory->ebooks()->exists()) {
            return redirect()
                ->route('ebook-categories.index')
                ->with('status', 'Kategori ebook tidak bisa dihapus karena masih dipakai ebook.');
        }

        $ebookCategory->delete();

        $this->apiJsonCacheService->refreshEbookCategories();

        return redirect()
            ->route('ebook-categories.index')
            ->with('status', 'Kategori ebook berhasil dihapus.');
    }
}
