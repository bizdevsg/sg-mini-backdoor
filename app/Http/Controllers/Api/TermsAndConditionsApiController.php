<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;

class TermsAndConditionsApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function show(): JsonResponse
    {
        $this->apiJsonCacheService->ensureTermsAndConditionsCache();
        $terms = $this->apiJsonCacheService->termsAndConditionsItem();

        abort_if($terms === null, 404);

        return response()->json([
            'data' => $terms,
        ]);
    }
}
