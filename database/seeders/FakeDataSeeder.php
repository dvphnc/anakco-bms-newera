<?php

namespace Database\Seeders;

use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\Household;
use App\Models\Official;
use App\Models\Resident;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Seeding fake data...');

        // Step 0 — Make sure puroks exist first
        $this->call([
            PurokSeeder::class,
        ]);

        // Step 1 — Households (must come before residents)
        $this->command->info('Creating 150 households...');
        Household::factory(150)->create();

        // Step 2 — Residents
        $this->command->info('Creating 600 residents...');
        Resident::factory(600)->create();

        // Step 3 — Documents
        $this->command->info('Creating 500 documents...');
        Document::factory(500)->create();

        // Step 4 — Blotter Cases
        $this->command->info('Creating 200 blotter cases...');
        BlotterCase::factory(200)->create();

        // Step 5 — Businesses
        $this->command->info('Creating 300 businesses...');
        Business::factory(300)->create();

        // Step 6 — Officials (just a few extras beyond the real ones)
        $this->command->info('Creating 10 extra officials...');
        Official::factory(10)->create();

        $this->command->info('✅ Done! Fake data seeded successfully.');
        $this->command->table(
            ['Model', 'Total Records'],
            [
                ['Households',    Household::count()],
                ['Residents',     Resident::count()],
                ['Documents',     Document::count()],
                ['Blotter Cases', BlotterCase::count()],
                ['Businesses',    Business::count()],
                ['Officials',     Official::count()],
            ]
        );
    }
}
