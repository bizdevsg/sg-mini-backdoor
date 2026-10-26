<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientAreaTiktokVideo\StoreClientAreaTiktokVideoRequest;
use App\Http\Requests\ClientAreaTiktokVideo\UpdateClientAreaTiktokVideoRequest;
use App\Models\ClientAreaTiktokVideo;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientAreaTiktokVideoController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {}

    public function index(Request $request): View
    {
        $videos = ClientAreaTiktokVideo::query()
            ->when(
                $request->string('search')->isNotEmpty(),
                function ($query) use ($request): void {
                    $search = $request->string('search')->toString();

                    $query->where(function ($builder) use ($search): void {
                        $builder
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('video_id', 'like', "%{$search}%")
                            ->orWhere('video_url', 'like', "%{$search}%");
                    });
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('client-area-tiktok-videos.index', [
            'videos' => $videos,
            'activeCount' => ClientAreaTiktokVideo::query()->where('is_active', true)->count('*'),
            'inactiveCount' => ClientAreaTiktokVideo::query()->where('is_active', false)->count('*'),
        ]);
    }

    public function create(): View
    {
        return view('client-area-tiktok-videos.create');
    }

    public function store(StoreClientAreaTiktokVideoRequest $request): RedirectResponse
    {
        ClientAreaTiktokVideo::query()->create($request->validated());
        $this->apiJsonCacheService->refreshClientAreaTiktokVideo();

        return redirect()
            ->route('tiktok-videos.index')
            ->with('status', 'Video TikTok berhasil ditambahkan.');
    }

    public function edit(ClientAreaTiktokVideo $clientAreaTiktokVideo): View
    {
        return view('client-area-tiktok-videos.edit', [
            'video' => $clientAreaTiktokVideo,
        ]);
    }

    public function update(
        UpdateClientAreaTiktokVideoRequest $request,
        ClientAreaTiktokVideo $clientAreaTiktokVideo,
    ): RedirectResponse {
        $clientAreaTiktokVideo->update($request->validated());
        $this->apiJsonCacheService->refreshClientAreaTiktokVideo();

        return redirect()
            ->route('tiktok-videos.index')
            ->with('status', 'Video TikTok berhasil diperbarui.');
    }

    public function destroy(ClientAreaTiktokVideo $clientAreaTiktokVideo): RedirectResponse
    {
        $clientAreaTiktokVideo->delete();
        $this->apiJsonCacheService->refreshClientAreaTiktokVideo();

        return redirect()
            ->route('tiktok-videos.index')
            ->with('status', 'Video TikTok berhasil dihapus.');
    }
}
