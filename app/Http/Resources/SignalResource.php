<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Signal */
class SignalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'title' => strtoupper($this->potensi) . ' ' . $this->timeframe,
            'potensi' => $this->potensi,
            'timeframe' => $this->timeframe,
            'taking_profit' => $this->taking_profit,
            'stop_loss' => $this->stop_loss,
            'sumber' => $this->sumber,
            'source' => $this->sumber,
            'kategori' => $this->category?->name,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
