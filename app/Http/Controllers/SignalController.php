<?php

namespace App\Http\Controllers;

use App\Http\Requests\Signal\StoreSignalRequest;
use App\Http\Requests\Signal\UpdateSignalRequest;
use App\Models\Signal;
use App\Models\SignalCategory;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SignalController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request, SignalCategory $signalCategory): View
    {
        $search = $request->string('search')->toString();

        $signals = Signal::query()
            ->with('category')
            ->whereBelongsTo($signalCategory, 'category')
            ->applySearch($search)
            ->orderForListing()
            ->paginate(20)
            ->withQueryString();

        return view('signal.index', [
            'signalCategory' => $signalCategory,
            'signals' => $signals,
        ]);
    }

    public function create(SignalCategory $signalCategory): View
    {
        return view('signal.create', [
            'signalCategory' => $signalCategory,
        ]);
    }

    public function store(StoreSignalRequest $request, SignalCategory $signalCategory): RedirectResponse
    {
        $validated = $request->safe()->except('image');
        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/signal');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }
        }

        Signal::create(Signal::prepareForPersistence([
            ...$validated,
            'signal_category_id' => $signalCategory->id,
            'image' => $imagePath,
        ]));

        $this->apiJsonCacheService->refreshSignal();
        $this->apiJsonCacheService->refreshSignalCategories();

        return redirect()
            ->route('signal.index', $signalCategory)
            ->with('status', 'Signal berhasil ditambahkan.');
    }

    public function show(SignalCategory $signalCategory, Signal $signal): View
    {
        $this->ensureCategoryMatchesSignal($signalCategory, $signal);
        $signal->load('category');

        return view('signal.show', [
            'signalCategory' => $signalCategory,
            'signal' => $signal,
        ]);
    }

    public function edit(SignalCategory $signalCategory, Signal $signal): View
    {
        $this->ensureCategoryMatchesSignal($signalCategory, $signal);
        $signal->load('category');

        return view('signal.edit', [
            'signalCategory' => $signalCategory,
            'signal' => $signal,
        ]);
    }

    public function update(UpdateSignalRequest $request, SignalCategory $signalCategory, Signal $signal): RedirectResponse
    {
        $this->ensureCategoryMatchesSignal($signalCategory, $signal);
        $validated = $request->safe()->except('image');
        $imagePath = $signal->image;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/signal');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($signal->image);
        }

        $signal->update(Signal::prepareForPersistence([
            ...$validated,
            'signal_category_id' => $signalCategory->id,
            'image' => $imagePath,
        ]));

        $this->apiJsonCacheService->refreshSignal();
        $this->apiJsonCacheService->refreshSignalCategories();

        return redirect()
            ->route('signal.index', $signalCategory)
            ->with('status', 'Signal berhasil diperbarui.');
    }

    public function destroy(SignalCategory $signalCategory, Signal $signal): RedirectResponse
    {
        $this->ensureCategoryMatchesSignal($signalCategory, $signal);
        $this->optimizedImageStorage->delete($signal->image);
        $signal->delete();

        $this->apiJsonCacheService->refreshSignal();
        $this->apiJsonCacheService->refreshSignalCategories();

        return redirect()
            ->route('signal.index', $signalCategory)
            ->with('status', 'Signal berhasil dihapus.');
    }

    private function ensureCategoryMatchesSignal(SignalCategory $signalCategory, Signal $signal): void
    {
        abort_unless($signal->signal_category_id === $signalCategory->id, 404);
    }
}
