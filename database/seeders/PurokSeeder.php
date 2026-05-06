<?php

namespace Database\Seeders;

use App\Models\Purok;
use Illuminate\Database\Seeder;

class PurokSeeder extends Seeder
{
    public function run(): void
    {
        $puroks = [
            ['name' => 'Purok 1 - Sampaguita', 'description' => 'Zone 1'],
            ['name' => 'Purok 2 - Rosal',       'description' => 'Zone 2'],
            ['name' => 'Purok 3 - Ilang-Ilang', 'description' => 'Zone 3'],
            ['name' => 'Purok 4 - Gumamela',    'description' => 'Zone 4'],
            ['name' => 'Purok 5 - Dahlia',      'description' => 'Zone 5'],
            ['name' => 'Purok 6 - Camia',       'description' => 'Zone 6'],
        ];

        foreach ($puroks as $purok) {
            Purok::create($purok);
        }
    }
}
