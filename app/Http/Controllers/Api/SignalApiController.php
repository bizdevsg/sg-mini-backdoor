<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SignalApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureSignalCache();
        $this->apiJsonCacheService->ensureSignalCategoryCache();

        $items = $this->apiJsonCacheService->signalItems();
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
        $this->apiJsonCacheService->ensureSignalCache();

        $signal = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->signalItems(),
            $slug
        );

        abort_if($signal === null, 404);

        return response()->json([
            'data' => $signal,
        ]);
    }
}
