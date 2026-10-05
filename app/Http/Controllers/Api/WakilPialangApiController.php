<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WakilPialang;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WakilPialangApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureWakilPialangCache();

        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $page = max(1, (int) $request->integer('page', 1));
        $status = $request->string('status')->toString();
        $category = $request->string('category')->toString();

        $items = array_values(array_filter(
            $this->apiJsonCacheService->wakilPialangItems(),
            fn (array $item): bool => (! array_key_exists($status, WakilPialang::STATUS_OPTIONS) || $item['status'] === $status)
                && ($category === '' || data_get($item, 'category.slug') === $category)
        ));
        $items = $this->apiJsonCacheService->search(
            $items,
            $request->string('search')->toString(),
            ['nama', 'no_identitas', 'category.nama_kategori']
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

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureWakilPialangCache();

        $item = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->wakilPialangItems(),
            $slug
        );

        abort_if($item === null, 404);

        return response()->json([
            'data' => $item,
        ]);
    }
}
