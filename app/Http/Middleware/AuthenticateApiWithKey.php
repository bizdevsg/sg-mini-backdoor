<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiWithKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredKey = (string) config('api-auth.key');
        $headerName = (string) config('api-auth.header', 'X-API-Key');
        $providedKey = (string) $request->header($headerName, '');

        if ($configuredKey === '') {
            return $this->forbidden('API key belum dikonfigurasi.');
        }

        if ($providedKey === '' || ! hash_equals($configuredKey, $providedKey)) {
            return $this->forbidden('API key tidak valid.');
        }

        return $next($request);
    }

    private function forbidden(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 403);
    }
}
