<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientAreaTiktokVideo\StoreClientAreaTiktokVideoRequest;
use App\Http\Requests\ClientAreaTiktokVideo\UpdateClientAreaTiktokVideoRequest;
use App\Models\ClientAreaTiktokVideo;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        $data = $request->safe()->except('video_file');
        if ($request->hasFile('video_file')) {
            $data['file_path'] = $this->storeVideoFile($request);
        }
        ClientAreaTiktokVideo::query()->create($data);
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
        $data = $request->safe()->except('video_file');
        $oldFile = $clientAreaTiktokVideo->file_path;
        if ($request->hasFile('video_file')) {
            $data['file_path'] = $this->storeVideoFile($request);
        }
        $clientAreaTiktokVideo->update($data);
        if (isset($data['file_path']) && $oldFile) {
            Storage::disk('public')->delete($oldFile);
        }
        $this->apiJsonCacheService->refreshClientAreaTiktokVideo();

        return redirect()
            ->route('tiktok-videos.index')
            ->with('status', 'Video TikTok berhasil diperbarui.');
    }

    public function destroy(ClientAreaTiktokVideo $clientAreaTiktokVideo): RedirectResponse
    {
        $filePath = $clientAreaTiktokVideo->file_path;
        $clientAreaTiktokVideo->delete();
        if ($filePath) {
            Storage::disk('public')->delete($filePath);
        }
        $this->apiJsonCacheService->refreshClientAreaTiktokVideo();

        return redirect()
            ->route('tiktok-videos.index')
            ->with('status', 'Video TikTok berhasil dihapus.');
    }

    private function storeVideoFile(Request $request): string
    {
        $path = $request->file('video_file')->store('videos', 'public');

        if ($path === false) {
            throw ValidationException::withMessages(['video_file' => 'File video gagal disimpan.']);
        }

        return $path;
    }
}
