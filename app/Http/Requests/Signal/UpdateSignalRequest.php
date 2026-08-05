<?php

namespace App\Http\Requests\Signal;

use App\Models\Signal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSignalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('signal_categories', 'id')],
            'potensi' => ['required', Rule::in(Signal::POTENSI_OPTIONS)],
            'timeframe' => ['required', Rule::in(Signal::TIMEFRAME_OPTIONS)],
            'taking_profit' => ['required', 'string', 'max:100'],
            'stop_loss' => ['required', 'string', 'max:100'],
            'sumber' => ['required', 'string', 'max:150'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
        ];
    }
}
