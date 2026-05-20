<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admins are always auto-verified on creation
        User::updateOrCreate(['email' => 'admin@bms.gov.ph'], [
            'name'               => 'BMS Administrator',
            'role'               => 'Admin',
            'password'           => Hash::make('Admin@12345'),
            'email_verified_at'  => now(),
        ]);

        User::updateOrCreate(['email' => 'secretary@bms.gov.ph'], [
            'name'     => 'BMS Secretary',
            'role'     => 'Secretary',
            'password' => Hash::make('Secretary@12345'),
        ]);
    }
}
