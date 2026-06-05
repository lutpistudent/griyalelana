<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner account
        User::updateOrCreate(
            ['email' => 'owner@griyalelana.com'],
            [
                'name' => 'Owner Griya Lelana',
                'phone' => '6281234567890',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'email_verified_at' => now(),
                'two_factor_enabled' => true,
            ]
        );

        // Demo tenant accounts
        User::updateOrCreate(
            ['email' => 'tenant1@example.com'],
            [
                'name' => 'Budi Santoso',
                'phone' => '6281234567891',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'guardian_name' => 'Hadi Santoso',
                'guardian_phone' => '6281234567899',
                'guardian_relation' => 'Orang Tua',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'tenant2@example.com'],
            [
                'name' => 'Siti Rahayu',
                'phone' => '6281234567892',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'guardian_name' => 'Ahmad Rahayu',
                'guardian_phone' => '6281234567898',
                'guardian_relation' => 'Orang Tua',
                'email_verified_at' => now(),
            ]
        );
    }
}
