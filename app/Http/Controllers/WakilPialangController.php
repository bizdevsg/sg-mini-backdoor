<?php

namespace App\Http\Controllers;

use App\Http\Requests\WakilPialang\StoreWakilPialangRequest;
use App\Http\Requests\WakilPialang\UpdateWakilPialangRequest;
use App\Models\WakilPialang;
use App\Models\WakilPialangCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WakilPialangController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request, WakilPialangCategory $wakilPialangCategory): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $items = WakilPialang::query()
            ->whereBelongsTo($wakilPialangCategory, 'category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_identitas', 'like', "%{$search}%");
                });
            })
            ->when(array_key_exists($status, WakilPialang::STATUS_OPTIONS), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('wakil-pialang.index', [
            'wakilPialangCategory' => $wakilPialangCategory,
            'items' => $items,
        ]);
    }

    public function create(WakilPialangCategory $wakilPialangCategory): View
    {
        return view('wakil-pialang.create', [
            'wakilPialangCategory' => $wakilPialangCategory,
        ]);
    }

    public function store(StoreWakilPialangRequest $request, WakilPialangCategory $wakilPialangCategory): RedirectResponse
    {
        WakilPialang::create([
            ...$request->validated(),
            'id_kategori' => $wakilPialangCategory->id,
        ]);

        $this->refreshApiCache();

        return redirect()
            ->route('wakil-pialang.index', $wakilPialangCategory)
            ->with('status', 'Wakil pialang berjangka berhasil ditambahkan.');
    }

    public function edit(WakilPialangCategory $wakilPialangCategory, WakilPialang $wakilPialang): View
    {
        $this->ensureCategoryMatches($wakilPialangCategory, $wakilPialang);

        return view('wakil-pialang.edit', [
            'wakilPialangCategory' => $wakilPialangCategory,
            'wakilPialang' => $wakilPialang,
        ]);
    }

    public function update(UpdateWakilPialangRequest $request, WakilPialangCategory $wakilPialangCategory, WakilPialang $wakilPialang): RedirectResponse
    {
        $this->ensureCategoryMatches($wakilPialangCategory, $wakilPialang);

        $wakilPialang->update($request->validated());

        $this->refreshApiCache();

        return redirect()
            ->route('wakil-pialang.index', $wakilPialangCategory)
            ->with('status', 'Wakil pialang berjangka berhasil diperbarui.');
    }

    public function destroy(WakilPialangCategory $wakilPialangCategory, WakilPialang $wakilPialang): RedirectResponse
    {
        $this->ensureCategoryMatches($wakilPialangCategory, $wakilPialang);

        $wakilPialang->delete();

        $this->refreshApiCache();

        return redirect()
            ->route('wakil-pialang.index', $wakilPialangCategory)
            ->with('status', 'Wakil pialang berjangka berhasil dihapus.');
    }

    private function refreshApiCache(): void
    {
        $this->apiJsonCacheService->refreshWakilPialang();
        $this->apiJsonCacheService->refreshWakilPialangCategories();
    }

    private function ensureCategoryMatches(WakilPialangCategory $wakilPialangCategory, WakilPialang $wakilPialang): void
    {
        abort_unless($wakilPialang->id_kategori === $wakilPialangCategory->id, 404);
    }
}
