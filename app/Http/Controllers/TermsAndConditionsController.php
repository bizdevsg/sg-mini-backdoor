<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermsAndConditions\UpdateTermsAndConditionRequest;
use App\Models\TermsAndCondition;
use App\Support\ApiJsonCacheService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TermsAndConditionsController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function show(): View
    {
        return view('terms-and-conditions.index', [
            'terms' => TermsAndCondition::query()->first(),
        ]);
    }

    public function update(UpdateTermsAndConditionRequest $request): RedirectResponse
    {
        $terms = TermsAndCondition::query()->first() ?? new TermsAndCondition();
        $terms->fill($request->validated());
        $terms->save();
        $this->apiJsonCacheService->refreshTermsAndConditions();

        return redirect()
            ->route('terms-and-conditions.show')
            ->with('status', 'Syarat dan ketentuan berhasil diperbarui.');
    }
}
