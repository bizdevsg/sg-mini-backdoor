<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ClientAreaSettingStore;
use Illuminate\Http\JsonResponse;

class ClientAreaApiController extends Controller
{
    public function __construct(
        private readonly ClientAreaSettingStore $clientAreaSettingStore,
    ) {
    }

    public function show(): JsonResponse
    {
        $settings = $this->clientAreaSettingStore->get();

        return response()->json([
            'data' => [
                'dev' => $settings['client_area_dev'],
                'prod' => $settings['client_area_prod'],
            ],
        ]);
    }
}
