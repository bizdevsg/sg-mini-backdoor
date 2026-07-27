<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientArea\UpdateClientAreaSettingRequest;
use App\Support\ClientAreaSettingStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientAreaSettingController extends Controller
{
    public function __construct(
        private readonly ClientAreaSettingStore $clientAreaSettingStore,
    ) {
    }

    public function show(): View
    {
        return view('client-area.show', [
            'settings' => $this->clientAreaSettingStore->get(),
            'apiBaseUrl' => url('/api/v1/client-area'),
            'apiKeyHeader' => (string) config('api-auth.header', 'X-API-Key'),
        ]);
    }

    public function update(UpdateClientAreaSettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $target = (string) $validated['target'];
        $enabled = (bool) $validated['enabled'];

        $this->clientAreaSettingStore->set($target, $enabled);

        $environmentLabel = $target === 'prod' ? 'Production' : 'Development';
        $statusLabel = $enabled ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('client-area.show')
            ->with('status', "Client Area {$environmentLabel} berhasil {$statusLabel}.");
    }
}
