<?php

namespace App\Http\Controllers;

use App\Http\Requests\Berita\StoreBeritaRequest;
use App\Http\Requests\Berita\UpdateBeritaRequest;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Support\ApiJsonCacheService;
use App\Support\DuplicateEntryGuard;
use App\Support\OptimizedImageStorage;
use App\Support\PushNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BeritaController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
        private readonly PushNotificationService $pushNotificationService,
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

        try {
            $berita = Berita::create(Berita::prepareForPersistence([
                ...$validated,
                'berita_category_id' => $beritaCategory->id,
                'image' => $imagePath,
            ]));
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'title_id',
                'Berita dengan judul ini baru saja dibuat. Silakan gunakan judul yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshBerita();
        $this->apiJsonCacheService->refreshBeritaCategories();

        $result = $this->pushNotificationService->contentSaveMessage('Berita berhasil ditambahkan.', $request->user(), $berita, $berita->is_notification);

        return redirect()
            ->route('berita.index', $beritaCategory)
            ->with('status', $result['message'])
            ->with('status_type', $result['type']);
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
        $wasNotificationEnabled = $berita->is_notification;
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

        try {
            $berita->update(Berita::prepareForPersistence([
                ...$validated,
                'berita_category_id' => $beritaCategory->id,
                'image' => $imagePath,
            ]));
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'title_id',
                'Berita dengan judul ini baru saja dibuat. Silakan gunakan judul yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshBerita();
        $this->apiJsonCacheService->refreshBeritaCategories();

        $result = $this->pushNotificationService->contentSaveMessage('Berita berhasil diperbarui.', $request->user(), $berita, ! $wasNotificationEnabled && $berita->is_notification);

        return redirect()
            ->route('berita.index', $beritaCategory)
            ->with('status', $result['message'])
            ->with('status_type', $result['type']);
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
