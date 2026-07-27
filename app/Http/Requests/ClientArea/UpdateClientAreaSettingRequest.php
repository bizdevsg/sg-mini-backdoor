<?php

namespace App\Http\Requests\ClientArea;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientAreaSettingRequest extends FormRequest
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
            'target' => ['required', 'string', Rule::in(['dev', 'prod'])],
            'enabled' => ['required', 'boolean'],
        ];
    }
}
