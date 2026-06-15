<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InformasiApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensurePengumumanCache();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = $this->apiJsonCacheService->pengumumanItems();
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

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensurePengumumanCache();
        $informasi = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->pengumumanItems(),
            $slug
        );

        abort_if($informasi === null, 404);

        return response()->json([
            'data' => $informasi,
        ]);
    }
}
