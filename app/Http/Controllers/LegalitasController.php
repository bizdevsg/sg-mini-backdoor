<?php

namespace App\Http\Controllers;

use App\Http\Requests\Legalitas\StoreLegalitasRequest;
use App\Http\Requests\Legalitas\UpdateLegalitasRequest;
use App\Models\Legalitas;
use App\Support\ApiJsonCacheService;
use App\Support\DuplicateEntryGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LegalitasController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $legalitasItems = Legalitas::query()
            ->when(
                $request->string('search')->isNotEmpty(),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $search = $request->string('search')->toString();

                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('nomor', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('legalitas.index', [
            'legalitasItems' => $legalitasItems,
        ]);
    }

    public function create(): View
    {
        return view('legalitas.create');
    }

    public function store(StoreLegalitasRequest $request): RedirectResponse
    {
        try {
            Legalitas::create($request->validated());
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'title',
                'Legalitas dengan judul ini baru saja dibuat. Silakan gunakan judul yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshLegalitas();

        return redirect()
            ->route('legalitas.index')
            ->with('status', 'Legalitas berhasil ditambahkan.');
    }

    public function edit(Legalitas $legalitas): View
    {
        return view('legalitas.edit', [
            'legalitas' => $legalitas,
        ]);
    }

    public function update(UpdateLegalitasRequest $request, Legalitas $legalitas): RedirectResponse
    {
        try {
            $legalitas->update($request->validated());
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'title',
                'Legalitas dengan judul ini baru saja dibuat. Silakan gunakan judul yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshLegalitas();

        return redirect()
            ->route('legalitas.index')
            ->with('status', 'Legalitas berhasil diperbarui.');
    }

    public function destroy(Legalitas $legalitas): RedirectResponse
    {
        $legalitas->delete();
        $this->apiJsonCacheService->refreshLegalitas();

        return redirect()
            ->route('legalitas.index')
            ->with('status', 'Legalitas berhasil dihapus.');
    }
}
