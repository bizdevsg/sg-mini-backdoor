<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientArea\UpdateClientAreaSettingRequest;
use App\Support\ClientAreaSettingStore;
use App\Support\SystemActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientAreaSettingController extends Controller
{
    public function __construct(
        private readonly ClientAreaSettingStore $clientAreaSettingStore,
        private readonly SystemActivityLogger $systemActivityLogger,
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
        $beforeSettings = $this->clientAreaSettingStore->get();

        $this->clientAreaSettingStore->set($target, $enabled);

        $environmentLabel = $target === 'prod' ? 'Production' : 'Development';
        $statusLabel = $enabled ? 'diaktifkan' : 'dinonaktifkan';
        $field = $target === 'prod' ? 'client_area_prod' : 'client_area_dev';
        $previousStatus = (bool) ($beforeSettings[$field] ?? false);

        $this->systemActivityLogger->log(
            category: 'data',
            event: 'client_area_toggle',
            description: "Client Area {$environmentLabel} {$statusLabel}.",
            subject: 'client-area',
            user: $request->user(),
            request: $request,
            context: [
                'target' => $target,
                'previous_status' => $previousStatus,
                'new_status' => $enabled,
            ],
        );

        return redirect()
            ->route('client-area.show')
            ->with('status', "Client Area {$environmentLabel} berhasil {$statusLabel}.");
    }
}
