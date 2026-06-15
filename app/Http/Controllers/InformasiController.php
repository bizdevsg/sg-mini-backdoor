<?php

namespace App\Http\Controllers;

use App\Http\Requests\Informasi\StoreInformasiRequest;
use App\Http\Requests\Informasi\UpdateInformasiRequest;
use App\Models\Informasi;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InformasiController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $informasis = Informasi::query()
            ->when(
                $request->string('search')->isNotEmpty(),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $search = $request->string('search')->toString();

                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $this->apiJsonCacheService->ensurePengumumanCache();

        return view('pengumuman.index', [
            'informasis' => $informasis,
        ]);
    }

    public function create(): View
    {
        return view('pengumuman.create');
    }

    public function store(StoreInformasiRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('image');
        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'informasi-images');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }
        }

        Informasi::create([
            ...$validated,
            'image' => $imagePath,
        ]);

        $this->apiJsonCacheService->refreshPengumuman();

        return redirect()
            ->route('pengumuman.index')
            ->with('status', 'Pengumuman berhasil ditambahkan.');
    }

    public function show(Informasi $informasi): View
    {
        return view('pengumuman.show', [
            'informasi' => $informasi,
        ]);
    }

    public function edit(Informasi $informasi): View
    {
        return view('pengumuman.edit', [
            'informasi' => $informasi,
        ]);
    }

    public function update(UpdateInformasiRequest $request, Informasi $informasi): RedirectResponse
    {
        $validated = $request->safe()->except('image');
        $imagePath = $informasi->image;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'informasi-images');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($informasi->image);
        }

        $informasi->update([
            ...$validated,
            'image' => $imagePath,
        ]);

        $this->apiJsonCacheService->refreshPengumuman();

        return redirect()
            ->route('pengumuman.index')
            ->with('status', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Informasi $informasi): RedirectResponse
    {
        $this->optimizedImageStorage->delete($informasi->image);
        $informasi->delete();

        $this->apiJsonCacheService->refreshPengumuman();

        return redirect()
            ->route('pengumuman.index')
            ->with('status', 'Pengumuman berhasil dihapus.');
    }
}
