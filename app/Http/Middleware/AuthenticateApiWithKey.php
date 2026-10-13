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
        $configuredKeyHash = strtolower((string) config('api-auth.key_hash'));
        $headerName = (string) config('api-auth.header', 'X-API-Key');
        $providedKey = (string) $request->header($headerName, '');

        if (preg_match('/\A[a-f0-9]{64}\z/D', $configuredKeyHash) !== 1) {
            return $this->forbidden('API key belum dikonfigurasi.');
        }

        if ($providedKey === '' || ! hash_equals($configuredKeyHash, hash('sha256', $providedKey))) {
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
