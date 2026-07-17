<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EbookApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureEbookCache();
        $this->apiJsonCacheService->ensureEbookCategoryCache();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $categorySlug = $request->string('category')->toString();
        $items = $this->apiJsonCacheService->ebookItems();

        if ($categorySlug !== '') {
            $category = $this->apiJsonCacheService->findBySlug(
                $this->apiJsonCacheService->ebookCategoryItems(),
                $categorySlug
            );

            abort_if($category === null, 404);

            $items = array_values(array_filter(
                $items,
                fn (array $item): bool => data_get($item, 'category.slug') === $categorySlug
            ));
        }

        $items = $this->apiJsonCacheService->search($items, $search, ['title', 'kategori', 'category.name', 'slug']);

        return response()->json(
            $this->apiJsonCacheService->paginate(
                $items,
                $perPage,
                $page,
                $request->url(),
                array_filter($request->query())
            )
        );
    }

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureEbookCache();
        $ebook = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->ebookItems(),
            $slug
        );

        abort_if($ebook === null, 404);

        return response()->json([
            'data' => $ebook,
        ]);
    }
}
