<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LegalitasApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureLegalitasCache();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = $this->apiJsonCacheService->legalitasItems();
        $items = $this->apiJsonCacheService->search($items, $search, ['title', 'nomor', 'description', 'slug']);

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
        $this->apiJsonCacheService->ensureLegalitasCache();
        $legalitas = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->legalitasItems(),
            $slug
        );

        abort_if($legalitas === null, 404);

        return response()->json([
            'data' => $legalitas,
        ]);
    }
}
