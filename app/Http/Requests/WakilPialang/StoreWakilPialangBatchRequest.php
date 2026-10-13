<?php

namespace App\Http\Requests\WakilPialang;

use App\Models\WakilPialang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWakilPialangBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.nama' => ['required', 'string', 'max:150'],
            'items.*.no_identitas' => [
                'required',
                'string',
                'max:50',
                'distinct:ignore_case',
                Rule::unique('wakil_pialangs', 'no_identitas'),
            ],
            'items.*.status' => ['required', Rule::in(array_keys(WakilPialang::STATUS_OPTIONS))],
        ];
    }
}
