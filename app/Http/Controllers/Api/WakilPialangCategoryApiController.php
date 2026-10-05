<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WakilPialang;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WakilPialangCategoryApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureWakilPialangCategoryCache();
        $items = $this->apiJsonCacheService->search(
            $this->apiJsonCacheService->wakilPialangCategoryItems(),
            $request->string('search')->toString(),
            ['nama_kategori', 'slug', 'alamat_kantor_cabang', 'telp']
        );

        return response()->json(
            $this->apiJsonCacheService->paginate(
                array_values($items),
                (int) $request->integer('per_page', 20),
                (int) $request->integer('page', 1),
                $request->url(),
                array_filter($request->query())
            )
        );
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureWakilPialangCategoryCache();
        $this->apiJsonCacheService->ensureWakilPialangCache();

        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->wakilPialangCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        $status = $request->string('status')->toString();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $page = max(1, (int) $request->integer('page', 1));
        $items = array_values(array_filter(
            $this->apiJsonCacheService->wakilPialangItems(),
            fn (array $item): bool => data_get($item, 'category.slug') === $slug
                && (! array_key_exists($status, WakilPialang::STATUS_OPTIONS) || $item['status'] === $status)
        ));
        $items = $this->apiJsonCacheService->search(
            $items,
            $request->string('search')->toString(),
            ['nama', 'no_identitas']
        );

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
        $this->apiJsonCacheService->ensureWakilPialangCategoryCache();
        $this->apiJsonCacheService->ensureWakilPialangCache();

        $category = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->wakilPialangCategoryItems(),
            $slug
        );

        abort_if($category === null, 404);

        $items = array_values(array_filter(
            $this->apiJsonCacheService->wakilPialangItems(),
            fn (array $item): bool => data_get($item, 'category.slug') === $slug
        ));

        return response()->json([
            'data' => [
                ...$category,
                'wakil_pialangs' => $items,
            ],
        ]);
    }
}
