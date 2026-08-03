<?php

namespace App\Http\Requests\TradingviewSymbol;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTradingviewSymbolRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'symbol_ws' => ['required', 'string', 'max:100', Rule::unique('tradingview_symbols', 'symbol_ws')],
            'symbol_tv' => ['required', 'string', 'max:100'],
        ];
    }
}
