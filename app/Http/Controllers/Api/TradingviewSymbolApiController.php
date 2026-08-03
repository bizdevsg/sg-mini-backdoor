<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TradingviewSymbolApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiJsonCacheService->ensureTradingviewSymbolCache();
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $search = $request->string('search')->toString();
        $page = max(1, (int) $request->integer('page', 1));
        $items = $this->apiJsonCacheService->tradingviewSymbolItems();
        $items = $this->apiJsonCacheService->search($items, $search, ['name', 'symbol_ws', 'symbol_tv']);

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

    public function show(string $symbolWs): JsonResponse
    {
        $this->apiJsonCacheService->ensureTradingviewSymbolCache();
        $item = $this->apiJsonCacheService->findBySymbolWs(
            $this->apiJsonCacheService->tradingviewSymbolItems(),
            $symbolWs
        );

        abort_if($item === null, 404);

        return response()->json([
            'data' => $item,
        ]);
    }
}
