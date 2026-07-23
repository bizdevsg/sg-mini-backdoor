<?php

namespace App\Http\Requests\TermsAndConditions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTermsAndConditionRequest extends FormRequest
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
            'content' => ['required', 'string'],
            'content_en' => ['nullable', 'string'],
        ];
    }
}
