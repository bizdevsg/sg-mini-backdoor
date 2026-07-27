<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'client_area_dev',
    'client_area_prod',
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
        ];
    }
}
