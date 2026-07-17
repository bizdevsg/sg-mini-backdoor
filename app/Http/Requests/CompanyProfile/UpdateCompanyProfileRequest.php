<?php

namespace App\Http\Requests\CompanyProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_en' => ['nullable', 'string'],
            'mission' => ['nullable', 'array'],
            'mission.*' => ['nullable', 'string', 'max:255'],
            'mission_en' => ['nullable', 'array'],
            'mission_en.*' => ['nullable', 'string', 'max:255'],
            'vision' => ['nullable', 'array'],
            'vision.*' => ['nullable', 'string', 'max:255'],
            'vision_en' => ['nullable', 'array'],
            'vision_en.*' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'maps_embed_url' => ['nullable', 'url', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'fax' => ['nullable', 'string', 'max:100'],
            'complaint_link' => ['nullable', 'url', 'max:2000'],
        ];
    }
}
