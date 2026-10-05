<?php

namespace App\Http\Requests\WakilPialang;

use App\Models\WakilPialang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWakilPialangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                Rule::unique('wakil_pialangs', 'slug')->ignore($this->route('wakilPialang')),
            ],
            'no_identitas' => [
                'required',
                'string',
                'max:50',
                Rule::unique('wakil_pialangs', 'no_identitas')->ignore($this->route('wakilPialang')),
            ],
            'status' => ['required', Rule::in(array_keys(WakilPialang::STATUS_OPTIONS))],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => WakilPialang::generateSlug(
                $this->string('nama')->toString(),
                $this->route('wakilPialang')
            ),
        ]);
    }
}
