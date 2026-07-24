<?php

namespace App\Http\Requests\SignalCategory;

use App\Models\SignalCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSignalCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('signal_categories', 'slug')->ignore($this->route('signalCategory')),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => SignalCategory::generateSlug(
                $this->string('name')->toString(),
                $this->route('signalCategory')
            ),
        ]);
    }
}
