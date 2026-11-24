<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeritaApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCache();
        $this->apiJsonCacheService->ensureBeritaCategoryCache();

        $items = array_map(
            fn (array $item): array => $this->withBreakingNewsState($item),
            $this->apiJsonCacheService->beritaItems()
        );
        $search = $request->string('search')->toString();

        $breaking = filter_var($request->query('is_breaking_news'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($breaking !== null) {
            $items = array_values(array_filter(
                $items,
                fn (array $item): bool => (bool) ($item['is_breaking_news'] ?? false) === $breaking
            ));
        }

        $items = $this->apiJsonCacheService->search($items, $search, [
            'title',
            'title_id',
            'title_en',
            'author',
            'source',
            'slug',
            'kategori',
            'category.name',
            'content',
            'content_id',
            'content_en',
        ]);

        return response()->json(
            $this->apiJsonCacheService->paginate(
                $items,
                (int) $request->integer('per_page', 10),
                (int) $request->integer('page', 1),
                $request->url(),
                $request->query()
            )
        );
    }

    public function show(string $slug): JsonResponse
    {
        $this->apiJsonCacheService->ensureBeritaCache();

        $berita = $this->apiJsonCacheService->findBySlug(
            $this->apiJsonCacheService->beritaItems(),
            $slug
        );

        abort_if($berita === null, 404);

        return response()->json([
            'data' => $this->withBreakingNewsState($berita),
        ]);
    }

    /**
     * Cache JSON statis, jadi masa berlaku breaking news (1 jam) dihitung ulang tiap request.
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function withBreakingNewsState(array $item): array
    {
        $expiresAt = $item['breaking_news_expires_at'] ?? null;

        $item['is_breaking_news'] = (bool) ($item['is_breaking_news'] ?? false)
            && $expiresAt !== null
            && Carbon::parse($expiresAt)->isFuture();

        return $item;
    }
}
