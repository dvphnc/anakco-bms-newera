<?php

namespace Database\Seeders;

use App\Models\Purok;
use Illuminate\Database\Seeder;

/**
 * PurokSeeder
 * ──────────────────────────────────────────────────────────────────────────
 * Seeds the 6 puroks of Barangay New Era, District VI, Quezon City.
 * Named after Philippine flowers — a common naming tradition in NCR barangays.
 * Uses updateOrCreate so it is safe to re-run without creating duplicates.
 * ──────────────────────────────────────────────────────────────────────────
 */
class PurokSeeder extends Seeder
{
    public function run(): void
    {
        $puroks = [
            [
                'name'        => 'Purok 1 - Sampaguita',
                'description' => 'Zone 1 — Northern section of Barangay New Era',
            ],
            [
                'name'        => 'Purok 2 - Rosal',
                'description' => 'Zone 2 — Northwestern residential area',
            ],
            [
                'name'        => 'Purok 3 - Ilang-Ilang',
                'description' => 'Zone 3 — Central zone near the barangay hall',
            ],
            [
                'name'        => 'Purok 4 - Gumamela',
                'description' => 'Zone 4 — Eastern residential and commercial district',
            ],
            [
                'name'        => 'Purok 5 - Dahlia',
                'description' => 'Zone 5 — Southern section along the main road',
            ],
            [
                'name'        => 'Purok 6 - Camia',
                'description' => 'Zone 6 — Southwestern boundary zone',
            ],
        ];

        foreach ($puroks as $purok) {
            Purok::updateOrCreate(
                ['name' => $purok['name']],
                ['description' => $purok['description']]
            );
        }

        $this->command->info('✅  PurokSeeder: 6 puroks of Barangay New Era seeded.');
    }
}
