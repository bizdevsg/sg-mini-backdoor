<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonCacheService;
use Illuminate\Http\JsonResponse;

class CompanyProfileController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function show(): JsonResponse
    {
        $this->apiJsonCacheService->ensureCompanyProfileCache();
        $profile = $this->apiJsonCacheService->companyProfileItem();

        abort_if($profile === null, 404);

        return response()->json([
            'data' => $profile,
        ]);
    }
}
