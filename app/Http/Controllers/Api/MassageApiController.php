<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Massage\StoreMassageRequest;
use App\Models\Massage;
use Illuminate\Http\JsonResponse;

class MassageApiController extends Controller
{
    public function store(StoreMassageRequest $request): JsonResponse
    {
        $massage = Massage::query()->create($request->validated());

        return response()->json([
            'message' => 'Pesan berhasil dikirim.',
            'data' => [
                'id' => $massage->id,
                'id_laporan' => $massage->id_laporan,
                'nama' => $massage->nama,
                'email' => $massage->email,
                'no_tlp' => $massage->no_tlp,
                'subjek' => $massage->subjek,
                'massage' => $massage->massage,
                'created_at' => $massage->created_at?->toJSON(),
            ],
        ], 201);
    }
}
