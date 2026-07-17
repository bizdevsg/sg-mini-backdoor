<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyProfile\UpdateCompanyProfileRequest;
use App\Models\CompanyProfile;
use App\Support\ApiJsonCacheService;
use App\Support\CompanyProfileStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class CompanyProfileController extends Controller
{
    public function __construct(
        private readonly CompanyProfileStore $companyProfileStore,
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function show(): View
    {
        return view('company-profile.show', [
            'profile' => $this->currentProfile(),
        ]);
    }

    public function update(UpdateCompanyProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $payload = [
            ...$validated,
            'mission' => $this->normalizeItems($validated['mission'] ?? []),
            'mission_en' => $this->normalizeItems($validated['mission_en'] ?? []),
            'vision' => $this->normalizeItems($validated['vision'] ?? []),
            'vision_en' => $this->normalizeItems($validated['vision_en'] ?? []),
        ];

        if (Schema::hasTable('company_profiles')) {
            $profile = CompanyProfile::query()->first() ?? new CompanyProfile();
            $profile->fill($payload);
            $profile->save();
            $this->apiJsonCacheService->refreshCompanyProfile();
        } else {
            $this->companyProfileStore->put($payload);
        }

        return redirect()
            ->route('company-profile.show')
            ->with('status', 'Profil perusahaan berhasil diperbarui.');
    }

    /**
     * @return array<int, string>
     */
    private function normalizeItems(array $items): array
    {
        return array_values(array_filter(array_map(
            static fn (mixed $item) => trim((string) $item),
            $items
        )));
    }

    /**
     * @return array<string, mixed>
     */
    private function currentProfile(): array
    {
        if (! Schema::hasTable('company_profiles')) {
            return $this->companyProfileStore->get();
        }

        $profile = CompanyProfile::query()->first();

        if (! $profile) {
            return $this->companyProfileStore->get();
        }

        return [
            'company_name' => (string) $profile->company_name,
            'description' => (string) $profile->description,
            'description_en' => (string) ($profile->description_en ?? ''),
            'mission' => $this->normalizeItems((array) ($profile->mission ?? [])),
            'mission_en' => $this->normalizeItems((array) ($profile->mission_en ?? [])),
            'vision' => $this->normalizeItems((array) ($profile->vision ?? [])),
            'vision_en' => $this->normalizeItems((array) ($profile->vision_en ?? [])),
            'address' => (string) $profile->address,
            'maps_embed_url' => (string) ($profile->maps_embed_url ?? ''),
            'phone' => (string) ($profile->phone ?? ''),
            'email' => (string) ($profile->email ?? ''),
            'fax' => (string) ($profile->fax ?? ''),
            'complaint_link' => (string) ($profile->complaint_link ?? ''),
        ];
    }
}
