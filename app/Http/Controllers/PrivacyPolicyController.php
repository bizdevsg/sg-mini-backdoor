<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrivacyPolicy\UpdatePrivacyPolicyRequest;
use App\Models\PrivacyPolicy;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PrivacyPolicyController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function show(): View
    {
        return view('privacy-policy.show', [
            'policy' => PrivacyPolicy::query()->first(),
        ]);
    }

    public function update(UpdatePrivacyPolicyRequest $request): RedirectResponse
    {
        $policy = PrivacyPolicy::query()->first() ?? new PrivacyPolicy();
        $policy->fill($request->validated());
        $policy->save();
        $this->apiJsonCacheService->refreshPrivacyPolicy();

        return redirect()
            ->route('privacy-policy.show')
            ->with('status', 'Kebijakan privasi berhasil diperbarui.');
    }
}
