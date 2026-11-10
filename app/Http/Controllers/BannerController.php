<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Models\Banner;
use App\Support\ApiJsonCacheService;
use App\Support\DuplicateEntryGuard;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class BannerController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(): View
    {
        $banners = Banner::query()
            ->where('type', $this->bannerType())
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(20);

        return view('banner.index', [
            'banners' => $banners,
            'activeCount' => Banner::query()->where('type', $this->bannerType())->where('is_active', true)->count('*'),
            'inactiveCount' => Banner::query()->where('type', $this->bannerType())->where('is_active', false)->count('*'),
            ...$this->viewContext(),
        ]);
    }

    public function create(): View
    {
        return view('banner.create', $this->viewContext());
    }

    public function store(StoreBannerRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('image');

        try {
            $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/banner');
        } catch (\RuntimeException $exception) {
            throw ValidationException::withMessages([
                'image' => $exception->getMessage(),
            ]);
        }

        try {
            Banner::create([
                ...$validated,
                'image' => $imagePath,
                'type' => $this->bannerType(),
            ]);
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'title',
                'Banner dengan judul ini baru saja dibuat. Silakan gunakan judul yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshBanner();

        return redirect()
            ->route($this->routePrefix().'.index')
            ->with('status', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner): View
    {
        $this->ensureType($banner);

        return view('banner.edit', [
            'banner' => $banner,
            ...$this->viewContext(),
        ]);
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        $this->ensureType($banner);
        $validated = $request->safe()->except('image');
        $imagePath = $banner->image;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->optimizedImageStorage->store($request->file('image'), 'uploads/banner');
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image' => $exception->getMessage(),
                ]);
            }

            $this->optimizedImageStorage->delete($banner->image);
        }

        try {
            $banner->update([
                ...$validated,
                'image' => $imagePath,
            ]);
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'title',
                'Banner dengan judul ini baru saja dibuat. Silakan gunakan judul yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshBanner();

        return redirect()
            ->route($this->routePrefix().'.index')
            ->with('status', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->ensureType($banner);
        $this->optimizedImageStorage->delete($banner->image);
        $banner->delete();

        $this->apiJsonCacheService->refreshBanner();

        return redirect()
            ->route($this->routePrefix().'.index')
            ->with('status', 'Banner berhasil dihapus.');
    }

    private function routePrefix(): string
    {
        return request()->routeIs('banner-mini-micro.*') ? 'banner-mini-micro' : 'banner';
    }

    private function bannerType(): string
    {
        return $this->routePrefix() === 'banner-mini-micro' ? Banner::TYPE_MINI_MICRO : Banner::TYPE_REGULAR;
    }

    private function ensureType(Banner $banner): void
    {
        abort_unless($banner->type === $this->bannerType(), 404);
    }

    private function viewContext(): array
    {
        return [
            'routePrefix' => $this->routePrefix(),
            'bannerTypeLabel' => $this->bannerType() === Banner::TYPE_MINI_MICRO ? 'Mini Micro' : 'Reguler',
        ];
    }
}
