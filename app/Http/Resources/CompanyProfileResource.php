<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CompanyProfile */
class CompanyProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'mission' => array_values((array) ($this->mission ?? [])),
            'mission_en' => array_values((array) ($this->mission_en ?? [])),
            'vision' => array_values((array) ($this->vision ?? [])),
            'vision_en' => array_values((array) ($this->vision_en ?? [])),
            'address' => $this->address,
            'maps_embed_url' => $this->maps_embed_url,
            'phone' => $this->phone,
            'email' => $this->email,
            'fax' => $this->fax,
            'complaint_link' => $this->complaint_link,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
