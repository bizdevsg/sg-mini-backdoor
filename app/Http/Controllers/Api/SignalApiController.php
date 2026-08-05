<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SignalApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureSignalCache();
        $this->apiJsonCacheService->ensureSignalCategoryCache();

        $items = $this->apiJsonCacheService->signalItems();
        $search = $request->string('search')->toString();

        $items = $this->apiJsonCacheService->search($items, $search, [
            'potensi',
            'timeframe',
            'taking_profit',
            'stop_loss',
            'sumber',
            'source',
            'kategori',
            'category.name',
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

    public function show(int $signalId): JsonResponse
    {
        $this->apiJsonCacheService->ensureSignalCache();

        $signal = collect($this->apiJsonCacheService->signalItems())
            ->first(fn (array $item): bool => (int) data_get($item, 'id') === $signalId);

        abort_if($signal === null, 404);

        return response()->json([
            'data' => $signal,
        ]);
    }
}
