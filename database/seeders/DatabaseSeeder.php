<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,   // First — creates admin & secretary accounts
            PurokSeeder::class,  // Second — creates the 6 default puroks
        ]);
    }
}