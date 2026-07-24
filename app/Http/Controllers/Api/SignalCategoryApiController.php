<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;

class SignalCategoryApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(): JsonResponse
    {
        $this->apiJsonCacheService->ensureSignalCategoryCache();

        return response()->json([
            'data' => $this->apiJsonCacheService->signalCategoryItems(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureSignalCategoryCache();

        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->signalCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        return response()->json([
            'data' => $category,
        ]);
    }

    public function detail(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureSignalCategoryCache();
        $this->apiJsonCacheService->ensureSignalCache();

        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->signalCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        $signals = array_values(array_filter(
            $this->apiJsonCacheService->signalItems(),
            fn (array $item): bool => data_get($item, 'category.slug') === $slug
        ));

        return response()->json([
            'data' => [
                ...$category,
                'signals' => $signals,
            ],
        ]);
    }
}
