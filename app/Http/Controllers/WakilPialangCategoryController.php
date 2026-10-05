<?php

namespace App\Http\Controllers;

use App\Http\Requests\WakilPialangCategory\StoreWakilPialangCategoryRequest;
use App\Http\Requests\WakilPialangCategory\UpdateWakilPialangCategoryRequest;
use App\Models\WakilPialangCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WakilPialangCategoryController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = WakilPialangCategory::query()
            ->withCount('wakilPialangs')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nama_kategori', 'like', "%{$search}%")
                        ->orWhere('alamat_kantor_cabang', 'like', "%{$search}%")
                        ->orWhere('telp', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_kategori')
            ->paginate(20)
            ->withQueryString();

        return view('wakil-pialang-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('wakil-pialang-categories.create');
    }

    public function store(StoreWakilPialangCategoryRequest $request): RedirectResponse
    {
        WakilPialangCategory::create($request->validated());

        $this->apiJsonCacheService->refreshWakilPialangCategories();

        return redirect()
            ->route('wakil-pialang-categories.index')
            ->with('status', 'Kategori wakil pialang berhasil ditambahkan.');
    }

    public function edit(WakilPialangCategory $wakilPialangCategory): View
    {
        return view('wakil-pialang-categories.edit', [
            'wakilPialangCategory' => $wakilPialangCategory,
        ]);
    }

    public function update(UpdateWakilPialangCategoryRequest $request, WakilPialangCategory $wakilPialangCategory): RedirectResponse
    {
        $wakilPialangCategory->update($request->validated());

        $this->apiJsonCacheService->refreshWakilPialangCategories();
        $this->apiJsonCacheService->refreshWakilPialang();

        return redirect()
            ->route('wakil-pialang-categories.index')
            ->with('status', 'Kategori wakil pialang berhasil diperbarui.');
    }

    public function destroy(WakilPialangCategory $wakilPialangCategory): RedirectResponse
    {
        if ($wakilPialangCategory->wakilPialangs()->exists()) {
            return redirect()
                ->route('wakil-pialang-categories.index')
                ->with('status', 'Kategori wakil pialang tidak bisa dihapus karena masih dipakai wakil pialang.');
        }

        $wakilPialangCategory->delete();

        $this->apiJsonCacheService->refreshWakilPialangCategories();

        return redirect()
            ->route('wakil-pialang-categories.index')
            ->with('status', 'Kategori wakil pialang berhasil dihapus.');
    }
}
