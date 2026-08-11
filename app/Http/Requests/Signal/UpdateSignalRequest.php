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
            'slug' => ['required', 'string', 'max:180', Rule::unique('signals', 'slug')->ignore($this->route('signal'))],
            'potensi' => ['required', Rule::in(Signal::POTENSI_OPTIONS)],
            'timeframe' => ['required', Rule::in(Signal::TIMEFRAME_OPTIONS)],
            'confident' => ['nullable', 'string', 'max:100'],
            'entry' => ['required', 'string', 'max:100'],
            'taking_profit' => ['required', 'string', 'max:100'],
            'stop_loss' => ['required', 'string', 'max:100'],
            'sumber' => ['required', 'string', 'max:150'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $category = $this->route('signalCategory');
        $signal = $this->route('signal');

        $this->merge([
            'slug' => Signal::generateSlug(
                $category?->name ?? '',
                $this->string('potensi')->toString(),
                $this->string('timeframe')->toString(),
                $signal,
            ),
        ]);
    }
}
