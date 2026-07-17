<?php

namespace App\Http\Requests\Massage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreMassageRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'no_tlp' => ['required', 'string', 'max:20'],
            'subjek' => ['required', 'string', 'max:100'],
            'massage' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => trim((string) $this->input('nama')),
            'email' => Str::lower(trim((string) $this->input('email'))),
            'no_tlp' => trim((string) $this->input('no_tlp')),
            'subjek' => trim((string) $this->input('subjek')),
            'massage' => trim((string) $this->input('massage')),
        ]);
    }
}
