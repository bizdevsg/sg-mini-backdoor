<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Models\Banner;
use App\Support\ApiJsonCacheService;
use App\Support\OptimizedImageStorage;
use Illuminate\Contracts\View\View;
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
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(20);

        return view('banner.index', [
            'banners' => $banners,
            'activeCount' => Banner::query()->where('is_active', true)->count('*'),
            'inactiveCount' => Banner::query()->where('is_active', false)->count('*'),
        ]);
    }

    public function create(): View
    {
        return view('banner.create');
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

        Banner::create([
            ...$validated,
            'image' => $imagePath,
        ]);

        $this->apiJsonCacheService->refreshBanner();

        return redirect()
            ->route('banner.index')
            ->with('status', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner): View
    {
        return view('banner.edit', [
            'banner' => $banner,
        ]);
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
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

        $banner->update([
            ...$validated,
            'image' => $imagePath,
        ]);

        $this->apiJsonCacheService->refreshBanner();

        return redirect()
            ->route('banner.index')
            ->with('status', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->optimizedImageStorage->delete($banner->image);
        $banner->delete();

        $this->apiJsonCacheService->refreshBanner();

        return redirect()
            ->route('banner.index')
            ->with('status', 'Banner berhasil dihapus.');
    }
}
