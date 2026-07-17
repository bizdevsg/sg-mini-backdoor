<?php

namespace App\Http\Controllers;

use App\Http\Requests\Penghargaan\StorePenghargaanRequest;
use App\Http\Requests\Penghargaan\UpdatePenghargaanRequest;
use App\Models\Penghargaan;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PenghargaanController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $penghargaans = Penghargaan::query()
            ->when(
                $request->string('search')->isNotEmpty(),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $search = $request->string('search')->toString();

                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $this->apiJsonCacheService->ensurePenghargaanCache();

        return view('penghargaan.index', [
            'penghargaans' => $penghargaans,
        ]);
    }

    public function create(): View
    {
        return view('penghargaan.create');
    }

    public function store(StorePenghargaanRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('image');
        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/penghargaan');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }
        }

        Penghargaan::create([
            ...$validated,
            'image' => $imagePath,
        ]);

        $this->apiJsonCacheService->refreshPenghargaan();

        return redirect()
            ->route('penghargaan.index')
            ->with('status', 'Penghargaan berhasil ditambahkan.');
    }

    public function edit(Penghargaan $penghargaan): View
    {
        return view('penghargaan.edit', [
            'penghargaan' => $penghargaan,
        ]);
    }

    public function update(UpdatePenghargaanRequest $request, Penghargaan $penghargaan): RedirectResponse
    {
        $validated = $request->safe()->except('image');
        $imagePath = $penghargaan->image;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/penghargaan');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($penghargaan->image);
        }

        $penghargaan->update([
            ...$validated,
            'image' => $imagePath,
        ]);

        $this->apiJsonCacheService->refreshPenghargaan();

        return redirect()
            ->route('penghargaan.index')
            ->with('status', 'Penghargaan berhasil diperbarui.');
    }

    public function destroy(Penghargaan $penghargaan): RedirectResponse
    {
        $this->optimizedImageStorage->delete($penghargaan->image);
        $penghargaan->delete();

        $this->apiJsonCacheService->refreshPenghargaan();

        return redirect()
            ->route('penghargaan.index')
            ->with('status', 'Penghargaan berhasil dihapus.');
    }
}
