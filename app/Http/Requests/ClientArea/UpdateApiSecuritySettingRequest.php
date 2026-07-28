<?php

namespace App\Http\Requests\ClientArea;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApiSecuritySettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-user-management') ?? false;
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'api_enabled' => ['required', 'boolean'],
            'api_key_rotation_notice' => ['nullable', 'string', 'max:255'],
            'allowed_origin_frontend' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
