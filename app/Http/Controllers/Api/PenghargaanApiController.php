<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PenghargaanApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = $this->apiJsonCacheService->penghargaanItems();
        $items = $this->apiJsonCacheService->search($items, $search, ['title', 'subtitle', 'slug']);

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
        $penghargaan = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->penghargaanItems(),
            $slug
        );

        abort_if($penghargaan === null, 404);

        return response()->json([
            'data' => $penghargaan,
        ]);
    }
}
