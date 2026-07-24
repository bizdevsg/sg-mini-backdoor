<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case AdminHost = 'admin_host';
    case Superadmin = 'superadmin';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::AdminHost => 'Admin Host',
            self::Superadmin => 'Superadmin',
        };
    }
}
