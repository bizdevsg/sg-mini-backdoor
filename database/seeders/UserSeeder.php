<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'name' => 'Superadmin SG',
                'email' => 'superadmin@sg-admin.test',
                'password' => 'password123',
                'role' => UserRole::Superadmin,
            ],
            [
                'name' => 'Admin SG',
                'email' => 'admin@sg-admin.test',
                'password' => 'password123',
                'role' => UserRole::Admin,
            ],
            [
                'name' => 'Admin Host SG',
                'email' => 'adminhost@sg-admin.test',
                'password' => 'password123',
                'role' => UserRole::AdminHost,
            ],
        ])->each(function (array $user): void {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                ],
            );
        });
    }
}
