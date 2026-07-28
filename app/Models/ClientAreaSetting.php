<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'client_area_dev',
    'client_area_prod',
    'tawk_to_enabled',
    'tawk_to_dev',
    'tawk_to_prod',
    'api_enabled',
    'api_key_rotation_notice',
    'allowed_origin_frontend',
])]
class ClientAreaSetting extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'client_area_dev' => 'boolean',
            'client_area_prod' => 'boolean',
            'tawk_to_enabled' => 'boolean',
            'tawk_to_dev' => 'boolean',
            'tawk_to_prod' => 'boolean',
            'api_enabled' => 'boolean',
        ];
    }
}
