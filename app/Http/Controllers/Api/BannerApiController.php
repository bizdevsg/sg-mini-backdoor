<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureBannerCache();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = $this->itemsForType($request);
        $items = $this->apiJsonCacheService->search($items, $search, ['title', 'slug']);

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

    public function show(Request $request, string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureBannerCache();
        $item = $this->apiJsonCacheService->findBySlug(
            $this->itemsForType($request),
            $slug
        );

        abort_if($item === null, 404);

        return response()->json([
            'data' => $item,
        ]);
    }

    private function itemsForType(Request $request): array
    {
        $type = $request->route('bannerType', Banner::TYPE_REGULAR);

        return array_values(array_filter(
            $this->apiJsonCacheService->bannerItems(),
            fn (array $item): bool => ($item['type'] ?? Banner::TYPE_REGULAR) === $type,
        ));
    }
}
