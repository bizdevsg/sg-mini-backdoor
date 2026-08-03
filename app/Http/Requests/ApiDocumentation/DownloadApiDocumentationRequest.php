<?php

namespace App\Http\Requests\ApiDocumentation;

use Illuminate\Foundation\Http\FormRequest;

class DownloadApiDocumentationRequest extends FormRequest
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
            'purpose' => ['required', 'string', 'max:500'],
            'recipient' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'purpose' => 'tujuan/kebutuhan',
            'recipient' => 'penerima',
        ];
    }
}
