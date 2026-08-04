<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignalCategory\StoreSignalCategoryRequest;
use App\Http\Requests\SignalCategory\UpdateSignalCategoryRequest;
use App\Models\SignalCategory;
use App\Support\ApiJsonCacheService;
use App\Support\DuplicateEntryGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SignalCategoryController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = SignalCategory::query()
            ->withCount('signals')
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

        return view('signal-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('signal-categories.create');
    }

    public function store(StoreSignalCategoryRequest $request): RedirectResponse
    {
        try {
            SignalCategory::create($request->validated());
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'name',
                'Kategori dengan nama ini baru saja dibuat. Silakan gunakan nama yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshSignalCategories();

        return redirect()
            ->route('signal-categories.index')
            ->with('status', 'Kategori signal berhasil ditambahkan.');
    }

    public function edit(SignalCategory $signalCategory): View
    {
        return view('signal-categories.edit', [
            'signalCategory' => $signalCategory,
        ]);
    }

    public function update(UpdateSignalCategoryRequest $request, SignalCategory $signalCategory): RedirectResponse
    {
        try {
            $signalCategory->update($request->validated());
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'name',
                'Kategori dengan nama ini baru saja dibuat. Silakan gunakan nama yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshSignalCategories();
        $this->apiJsonCacheService->refreshSignal();

        return redirect()
            ->route('signal-categories.index')
            ->with('status', 'Kategori signal berhasil diperbarui.');
    }

    public function destroy(SignalCategory $signalCategory): RedirectResponse
    {
        if ($signalCategory->signals()->exists()) {
            return redirect()
                ->route('signal-categories.index')
                ->with('status', 'Kategori signal tidak bisa dihapus karena masih dipakai signal.');
        }

        $signalCategory->delete();

        $this->apiJsonCacheService->refreshSignalCategories();

        return redirect()
            ->route('signal-categories.index')
            ->with('status', 'Kategori signal berhasil dihapus.');
    }
}
