<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProdukApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request, string $section): JsonResponse
    {
        $section = $this->resolveSection($section);
        $this->apiJsonCacheService->ensureProdukCache();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = $this->apiJsonCacheService->produkItems($section);
        $items = $this->apiJsonCacheService->search($items, $search, ['nama_produk', 'slug']);
        $items = array_map(
            fn (array $item): array => Arr::except($item, ['specs']),
            $items
        );

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

    public function show(string $section, string $slug): JsonResponse
    {
        $section = $this->resolveSection($section);
        $this->apiJsonCacheService->ensureProdukCache();
        $produk = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->produkItems($section),
            $slug
        );

        abort_if($produk === null, 404);

        return response()->json([
            'data' => $produk,
        ]);
    }

    private function resolveSection(string $section): string
    {
        return match ($section) {
            'spa' => 'spa',
            'jfx' => 'jfx',
            default => 'spa',
        };
    }
}
