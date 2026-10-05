<?php

namespace App\Http\Controllers;

use App\Support\CrudUndoService;
use DomainException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CrudUndoController extends Controller
{
    public function __invoke(Request $request, CrudUndoService $crudUndoService): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'size:64'],
        ]);

        try {
            $redirectUrl = $crudUndoService->undo($validated['token'], (int) $request->user()->getAuthIdentifier());
        } catch (DomainException|QueryException $exception) {
            $message = $exception instanceof DomainException
                ? $exception->getMessage()
                : 'Data tidak dapat dipulihkan karena berbenturan dengan perubahan terbaru.';

            return back()
                ->with('status', $message)
                ->with('status_type', 'error');
        }

        return redirect()->to($redirectUrl)
            ->with('status', 'Perubahan berhasil dibatalkan.');
    }
}
