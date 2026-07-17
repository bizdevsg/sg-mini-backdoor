<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_name',
    'description',
    'description_en',
    'mission',
    'mission_en',
    'vision',
    'vision_en',
    'address',
    'maps_embed_url',
    'phone',
    'email',
    'fax',
    'complaint_link',
])]
class CompanyProfile extends Model
{
    protected $table = 'company_profiles';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mission' => 'array',
            'mission_en' => 'array',
            'vision' => 'array',
            'vision_en' => 'array',
        ];
    }
}
