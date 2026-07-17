<?php

namespace App\Http\Controllers;

use App\Support\OptimizedImageStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TinyMceImageController extends Controller
{
    public function __construct(
        private readonly OptimizedImageStorage $optimizedImageStorage,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:10240'],
        ]);

        try {
            $path = $this->optimizedImageStorage->store($validated['image'], 'uploads/editor');
        } catch (\RuntimeException $exception) {
            throw ValidationException::withMessages([
                'image' => $exception->getMessage(),
            ]);
        }

        return response()->json([
            'location' => asset('storage/' . ltrim($path, '/')),
        ]);
    }
}
