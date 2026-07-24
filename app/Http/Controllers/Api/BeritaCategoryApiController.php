<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;

class BeritaCategoryApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCategoryCache();

        return response()->json([
            'data' => $this->apiJsonCacheService->beritaCategoryItems(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCategoryCache();

        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->beritaCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        return response()->json([
            'data' => $category,
        ]);
    }

    public function detail(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCategoryCache();
        $this->apiJsonCacheService->ensureBeritaCache();

        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->beritaCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        $beritas = array_values(array_filter(
            $this->apiJsonCacheService->beritaItems(),
            fn (array $item): bool => data_get($item, 'category.slug') === $slug
        ));

        return response()->json([
            'data' => [
                ...$category,
                'beritas' => $beritas,
            ],
        ]);
    }
}
