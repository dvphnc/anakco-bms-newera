<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'BMS Administrator',
            'email' => 'admin@bms.gov.ph',
            'role' => 'Admin',
            'password' => Hash::make('Admin@12345'),
        ]);

        User::create([
            'name' => 'BMS Secretary',
            'email' => 'secretary@bms.gov.ph',
            'role' => 'Secretary',
            'password' => Hash::make('Secretary@12345'),
        ]);
    }
}
