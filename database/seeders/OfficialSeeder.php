<?php

namespace Database\Seeders;

use App\Models\Official;
use Illuminate\Database\Seeder;

class OfficialSeeder extends Seeder
{
    /**
     * Seed the official Barangay New Era officials for the 2023–2026 term.
     * Uses updateOrCreate so it is safe to re-run without creating duplicates.
     */
    public function run(): void
    {
        $term_start = '2023-11-01';
        $term_end   = '2026-10-31';

        $officials = [
            // ── Elected Executive ─────────────────────────────────────────
            [
                'full_name'      => 'Robert Silva Romano',
                'position'       => 'Punong Barangay',
                'committee'      => null,
                'contact_number' => '(02) 5186818',
            ],

            // ── Administrative Officers ────────────────────────────────────
            [
                'full_name'      => 'Josephine Anthony Flores',
                'position'       => 'Barangay Secretary',
                'committee'      => null,
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Nessie Dela Cruz Tayag',
                'position'       => 'Barangay Treasurer',
                'committee'      => null,
                'contact_number' => null,
            ],

            // ── SK Chairperson ─────────────────────────────────────────────
            [
                'full_name'      => 'Ariel Caballero Madriaga',
                'position'       => 'SK Chairperson',
                'committee'      => 'BDRRM',
                'contact_number' => null,
            ],

            // ── Sangguniang Barangay Members (Kagawads) ────────────────────
            [
                'full_name'      => 'Salvador Lapid Enriquez',
                'position'       => 'Kagawad',
                'committee'      => 'Peace & Order',
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Euler Astete Moreno',
                'position'       => 'Kagawad',
                'committee'      => 'Environment',
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Joel Antonio Tamayo',
                'position'       => 'Kagawad',
                'committee'      => 'Livelihood',
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Freddie Cayabyab Marcial',
                'position'       => 'Kagawad',
                'committee'      => 'Infrastructure',
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Twinkle Besas Pineda-Corpuz',
                'position'       => 'Kagawad',
                'committee'      => 'Health',
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Alfredo Layco Sicat',
                'position'       => 'Kagawad',
                'committee'      => 'Transport & Comm.',
                'contact_number' => null,
            ],
            [
                'full_name'      => 'Medel Reyes Sulpico',
                'position'       => 'Kagawad',
                'committee'      => 'Education',
                'contact_number' => null,
            ],
        ];

        foreach ($officials as $data) {
            Official::updateOrCreate(
                ['full_name' => $data['full_name']],
                array_merge($data, [
                    'term_start' => $term_start,
                    'term_end'   => $term_end,
                    'is_active'  => true,
                ])
            );
        }

        $this->command->info('✅  OfficialSeeder: ' . count($officials) . ' Barangay New Era officials seeded.');
    }
}
