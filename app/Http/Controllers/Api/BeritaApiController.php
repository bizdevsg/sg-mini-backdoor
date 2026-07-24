<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeritaApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCache();
        $this->apiJsonCacheService->ensureBeritaCategoryCache();

        $items = $this->apiJsonCacheService->beritaItems();
        $search = $request->string('search')->toString();

        $items = $this->apiJsonCacheService->search($items, $search, [
            'title',
            'title_id',
            'title_en',
            'author',
            'source',
            'slug',
            'kategori',
            'category.name',
            'content',
            'content_id',
            'content_en',
        ]);

        return response()->json(
            $this->apiJsonCacheService->paginate(
                $items,
                (int) $request->integer('per_page', 10),
                (int) $request->integer('page', 1),
                $request->url(),
                $request->query()
            )
        );
    }

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCache();

        $berita = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->beritaItems(),
            $slug
        );

        abort_if($berita === null, 404);

        return response()->json([
            'data' => $berita,
        ]);
    }
}
