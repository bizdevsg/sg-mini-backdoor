<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ebook\StoreEbookRequest;
use App\Http\Requests\Ebook\UpdateEbookRequest;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EbookController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request, EbookCategory $ebookCategory): View
    {
        $search = $request->string('search')->toString();

        $ebooks = Ebook::query()
            ->with('category')
            ->whereBelongsTo($ebookCategory, 'category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('ebook.index', [
            'ebookCategory' => $ebookCategory,
            'ebooks' => $ebooks,
        ]);
    }

    public function create(EbookCategory $ebookCategory): View
    {
        return view('ebook.create', [
            'ebookCategory' => $ebookCategory,
        ]);
    }

    public function store(StoreEbookRequest $request, EbookCategory $ebookCategory): RedirectResponse
    {
        $validated = $request->safe()->except(['image', 'file']);
        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/ebook');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }
        }

        $filePath = $request->file('file')->store('ebook-files', 'public');

        Ebook::create([
            ...$validated,
            'ebook_category_id' => $ebookCategory->id,
            'image' => $imagePath,
            'file' => $filePath,
        ]);

        $this->apiJsonCacheService->refreshEbook();
        $this->apiJsonCacheService->refreshEbookCategories();

        return redirect()
            ->route('ebook.index', $ebookCategory)
            ->with('status', 'Ebook berhasil ditambahkan.');
    }

    public function show(EbookCategory $ebookCategory, Ebook $ebook): View
    {
        $this->ensureCategoryMatchesEbook($ebookCategory, $ebook);
        $ebook->load('category');

        return view('ebook.show', [
            'ebookCategory' => $ebookCategory,
            'ebook' => $ebook,
        ]);
    }

    public function edit(EbookCategory $ebookCategory, Ebook $ebook): View
    {
        $this->ensureCategoryMatchesEbook($ebookCategory, $ebook);
        $ebook->load('category');

        return view('ebook.edit', [
            'ebookCategory' => $ebookCategory,
            'ebook' => $ebook,
        ]);
    }

    public function update(UpdateEbookRequest $request, EbookCategory $ebookCategory, Ebook $ebook): RedirectResponse
    {
        $this->ensureCategoryMatchesEbook($ebookCategory, $ebook);
        $validated = $request->safe()->except(['image', 'file']);
        $imagePath = $ebook->image;
        $filePath = $ebook->file;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/ebook');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($ebook->image);
        }

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($ebook->file);
            $filePath = $request->file('file')->store('ebook-files', 'public');
        }

        $ebook->update([
            ...$validated,
            'ebook_category_id' => $ebookCategory->id,
            'image' => $imagePath,
            'file' => $filePath,
        ]);

        $this->apiJsonCacheService->refreshEbook();
        $this->apiJsonCacheService->refreshEbookCategories();

        return redirect()
            ->route('ebook.index', $ebookCategory)
            ->with('status', 'Ebook berhasil diperbarui.');
    }

    public function destroy(EbookCategory $ebookCategory, Ebook $ebook): RedirectResponse
    {
        $this->ensureCategoryMatchesEbook($ebookCategory, $ebook);
        $this->optimizedImageStorage->delete($ebook->image);
        Storage::disk('public')->delete($ebook->file);
        $ebook->delete();

        $this->apiJsonCacheService->refreshEbook();
        $this->apiJsonCacheService->refreshEbookCategories();

        return redirect()
            ->route('ebook.index', $ebookCategory)
            ->with('status', 'Ebook berhasil dihapus.');
    }

    private function ensureCategoryMatchesEbook(EbookCategory $ebookCategory, Ebook $ebook): void
    {
        abort_unless($ebook->ebook_category_id === $ebookCategory->id, 404);
    }
}
