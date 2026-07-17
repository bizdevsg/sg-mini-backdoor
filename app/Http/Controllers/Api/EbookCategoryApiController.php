<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EbookCategoryApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureEbookCategoryCache();
        $search = $request->string('search')->toString();
        $items = $this->apiJsonCacheService->ebookCategoryItems();
        $items = $this->apiJsonCacheService->search($items, $search, ['name', 'slug']);

        return response()->json([
            'data' => array_values($items),
        ]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureEbookCategoryCache();
        $this->apiJsonCacheService->ensureEbookCache();
        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->ebookCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = array_values(array_filter(
            $this->apiJsonCacheService->ebookItems(),
            fn (array $item): bool => data_get($item, 'category.slug') === $slug
        ));
        $items = $this->apiJsonCacheService->search($items, $search, ['title', 'kategori', 'category.name', 'slug']);

        return response()->json([
            ...$this->apiJsonCacheService->paginate(
                $items,
                $perPage,
                $page,
                $request->url(),
                array_filter($request->query())
            ),
            'category' => $category,
        ]);
    }

    public function detail(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureEbookCategoryCache();
        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->ebookCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        return response()->json([
            'data' => $category,
        ]);
    }
}
