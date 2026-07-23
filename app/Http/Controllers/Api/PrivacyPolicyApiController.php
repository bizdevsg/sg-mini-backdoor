<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;

class PrivacyPolicyApiController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function show(): JsonResponse
    {
        $this->apiJsonCacheService->ensurePrivacyPolicyCache();
        $policy = $this->apiJsonCacheService->privacyPolicyItem();

        abort_if($policy === null, 404);

        return response()->json([
            'data' => $policy,
        ]);
    }
}
