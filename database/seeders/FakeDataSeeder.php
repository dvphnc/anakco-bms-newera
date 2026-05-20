<?php

namespace Database\Seeders;

use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Database\Seeder;

/**
 * FakeDataSeeder
 * ──────────────────────────────────────────────────────────────────────────
 * Populates demo data proportional to Barangay New Era's real statistics.
 *
 * Source: barangaydirectory.com/barangay/quezon-city/new-era (2024 census)
 *   • Total population  : 14,987
 *   • Classification    : Urban, District VI, Quezon City
 *   • Avg household size: ~4.4 persons (PH 2020 census average)
 *
 * Derived counts:
 *   • Households  → 14,987 ÷ 4.4  ≈ 3,406  → 3,400
 *   • Residents   → 14,987 (full real population)
 *   • Documents   → ~20 % of pop. per year  → 3,000
 *   • Blotter     → realistic urban volume   → 350
 *   • Businesses  → urban barangay estimate  → 480
 *
 * Officials are seeded by OfficialSeeder (real 2023-2026 officials).
 * ──────────────────────────────────────────────────────────────────────────
 */
class FakeDataSeeder extends Seeder
{
    // ── Counts based on real Barangay New Era data ──────────────────────
    private const HOUSEHOLDS = 3_400;
    private const RESIDENTS  = 14_987;
    private const DOCUMENTS  = 3_000;
    private const BLOTTER    = 350;
    private const BUSINESSES = 480;

    // Batch size to avoid memory exhaustion when seeding large sets
    private const BATCH = 500;

    public function run(): void
    {
        $this->command->info('🌱 Seeding demo data for Barangay New Era (pop. 14,987)...');
        $this->command->newLine();

        // Step 0 — Make sure puroks & real officials exist first
        $this->call([
            PurokSeeder::class,
            OfficialSeeder::class,
        ]);

        // ── Step 1: Households ───────────────────────────────────────────
        $this->command->info(sprintf('Creating %s households (≈ pop ÷ 4.4 avg household size)…', number_format(self::HOUSEHOLDS)));
        $this->seedInBatches(fn ($n) => Household::factory($n)->create(), self::HOUSEHOLDS);

        // ── Step 2: Residents ────────────────────────────────────────────
        $this->command->info(sprintf('Creating %s residents (actual 2024 census population)…', number_format(self::RESIDENTS)));
        $this->seedInBatches(fn ($n) => Resident::factory($n)->create(), self::RESIDENTS);

        // ── Step 3: Documents ────────────────────────────────────────────
        $this->command->info(sprintf('Creating %s documents (≈ 20 %% annual issuance rate)…', number_format(self::DOCUMENTS)));
        $this->seedInBatches(fn ($n) => Document::factory($n)->create(), self::DOCUMENTS);

        // ── Step 4: Blotter Cases ────────────────────────────────────────
        $this->command->info(sprintf('Creating %s blotter cases…', number_format(self::BLOTTER)));
        $this->seedInBatches(fn ($n) => BlotterCase::factory($n)->create(), self::BLOTTER);

        // ── Step 5: Businesses ───────────────────────────────────────────
        $this->command->info(sprintf('Creating %s businesses…', number_format(self::BUSINESSES)));
        $this->seedInBatches(fn ($n) => Business::factory($n)->create(), self::BUSINESSES);

        // ── Summary ──────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('✅  Done! Demo data seeded based on real Barangay New Era statistics.');
        $this->command->newLine();
        $this->command->table(
            ['Model', 'Seeded', 'Basis'],
            [
                ['Households',    number_format(Household::count()),  'Pop ÷ 4.4 avg household size'],
                ['Residents',     number_format(Resident::count()),   '2024 census — 14,987 total'],
                ['Documents',     number_format(Document::count()),   '~20% annual issuance rate'],
                ['Blotter Cases', number_format(BlotterCase::count()),'Urban barangay estimate'],
                ['Businesses',    number_format(Business::count()),   'Urban barangay estimate'],
            ]
        );
    }

    /**
     * Run a factory callback in batches to prevent memory exhaustion.
     *
     * @param  callable $factory  Receives the batch count, should call factory(n)->create()
     * @param  int      $total
     */
    private function seedInBatches(callable $factory, int $total): void
    {
        $remaining = $total;
        while ($remaining > 0) {
            $batch = min(self::BATCH, $remaining);
            $factory($batch);
            $remaining -= $batch;
        }
    }
}
