<?php

namespace Database\Seeders;

use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\ResidentStatusLog;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

    // Two streets per purok, so every household on a street sits in the same purok
    // (puroks are named after their main street — see PurokSeeder).
    private const STREETS = [
        'Sampaguita' => 'Sampaguita', 'Luna'      => 'Sampaguita',
        'Rosal'      => 'Rosal',      'Mabini'    => 'Rosal',
        'Ilang-Ilang'=> 'Ilang-Ilang','Bonifacio' => 'Ilang-Ilang',
        'Gumamela'   => 'Gumamela',   'Kalayaan'  => 'Gumamela',
        'Dahlia'     => 'Dahlia',     'Rizal'     => 'Dahlia',
        'Camia'      => 'Camia',      'Katipunan' => 'Camia',
    ];

    private \Faker\Generator $faker;

    public function run(): void
    {
        // Demo data must never be mixed with real, confidential resident records.
        if (app()->isProduction()) {
            $this->command->error('FakeDataSeeder refused to run: APP_ENV is "production".');
            $this->command->error('Demo residents must never be mixed with real barangay records.');

            return;
        }

        $this->faker = FakerFactory::create('en_PH');

        $this->command->info('🌱 Seeding demo data for Barangay New Era (pop. 14,987)...');
        $this->command->newLine();

        // Step 0 — Make sure puroks & real officials exist first
        $this->call([
            PurokSeeder::class,
            OfficialSeeder::class,
        ]);

        // ── Steps 1–2: Families (households form automatically by address) ─
        $this->command->info(sprintf('Creating ~%s residents in family households (avg ≈ 4.4 per household)…', number_format(self::RESIDENTS)));
        $this->seedFamilies();

        // ── Step 3: Documents ────────────────────────────────────────────
        $this->command->info(sprintf('Creating %s documents (≈ 20 %% annual issuance rate)…', number_format(self::DOCUMENTS)));
        $this->seedInBatches(fn ($n) => Document::factory($n)->create(), self::DOCUMENTS);

        // ── Step 4: Blotter Cases ────────────────────────────────────────
        $this->command->info(sprintf('Creating %s blotter cases…', number_format(self::BLOTTER)));
        $this->seedInBatches(fn ($n) => BlotterCase::factory($n)->create(), self::BLOTTER);

        // ── Step 5: Businesses ───────────────────────────────────────────
        $this->command->info(sprintf('Creating %s businesses…', number_format(self::BUSINESSES)));
        $this->seedInBatches(fn ($n) => Business::factory($n)->create(), self::BUSINESSES);

        // ── Step 6: Committee Demo Data ──────────────────────────────────
        $this->command->info('Seeding committee activities, attendance, inventory & partnerships…');
        $this->call(CommitteeDataSeeder::class);

        // ── Step 7: Portal Appointments ──────────────────────────────────
        $this->command->info('Seeding portal appointment records…');
        $this->call(AppointmentSeeder::class);

        // ── Summary ──────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('✅  Done! Demo data seeded based on real Barangay New Era statistics.');
        $this->command->newLine();
        $this->command->table(
            ['Model', 'Seeded', 'Basis'],
            [
                ['Households',    number_format(Household::count()),  'Formed automatically by address'],
                ['Residents',     number_format(Resident::count()),   '2024 census — 14,987 total'],
                ['Documents',     number_format(Document::count()),   '~20% annual issuance rate'],
                ['Blotter Cases', number_format(BlotterCase::count()),'Urban barangay estimate'],
                ['Businesses',    number_format(Business::count()),   'Urban barangay estimate'],
            ]
        );
    }

    /**
     * Families: each household is one address shared by a head, usually a spouse,
     * children, and sometimes a parent, grandchildren or other relatives.
     * Residents are created through the normal model events, so households are
     * formed by HouseholdGroupingService exactly as they are in the app.
     * Afterwards some residents move out or die, with a dated status history.
     */
    private function seedFamilies(): void
    {
        $purokIds = Purok::pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [trim(explode('-', $name, 2)[1] ?? $name) => $id]);
        $adminId  = User::where('role', 'Admin')->value('id');
        $used     = [];   // "street|number" already taken
        $made     = 0;

        $bar = $this->command->getOutput()->createProgressBar(self::RESIDENTS);

        while ($made < self::RESIDENTS) {
            // A unique house on a street; the street decides the purok
            do {
                $street = array_rand(self::STREETS);
                $number = $this->faker->numberBetween(1, 1400);
            } while (isset($used["$street|$number"]));
            $used["$street|$number"] = true;

            $purokId  = $purokIds[self::STREETS[$street]] ?? $purokIds->first();
            $address  = "$number $street St., Barangay New Era, Quezon City";
            $surname  = null;
            $religion = $this->faker->randomElement(['Roman Catholic', 'Roman Catholic', 'Iglesia ni Cristo', 'Born Again Christian', 'Islam', 'Protestant']);
            $members  = array_slice($this->composeHousehold(), 0, self::RESIDENTS - $made);

            DB::transaction(function () use ($members, $purokId, $address, &$surname, $religion, $adminId) {
                $married = count(array_filter($members, fn ($m) => $m['rel'] === 'Spouse')) > 0;

                foreach ($members as $m) {
                    $attrs = [
                        'purok_id'             => $purokId,
                        'address'              => $address,
                        'birthdate'            => now()->subYears($m['age'])->subDays($this->faker->numberBetween(0, 364))->toDateString(),
                        'gender'               => $m['gender'],
                        'religion'             => $religion,
                        'relationship_to_head' => $m['rel'],
                        'residency_status'     => 'Active',   // life events are applied below, like in real use
                    ];
                    if ($m['family'] && $surname) {
                        $attrs['last_name'] = $surname;
                    }
                    if ($married && in_array($m['rel'], ['Head', 'Spouse'], true)) {
                        $attrs['civil_status'] = 'Married';
                    }

                    $resident = Resident::factory()->create($attrs);
                    $surname ??= $resident->last_name;

                    $this->applyLifeEvent($resident, $m['age'], $adminId);
                }
            });

            $made += count($members);
            $bar->advance(count($members));
        }

        $bar->finish();
        $this->command->newLine();
    }

    /** @return array<int, array{rel:string, age:int, gender:string, family:bool}> */
    private function composeHousehold(): array
    {
        $f = $this->faker;
        $headAge    = $f->numberBetween(24, 76);
        $headGender = $f->boolean(70) ? 'Male' : 'Female';
        $other      = fn ($g) => $g === 'Male' ? 'Female' : 'Male';
        $any        = fn () => $f->randomElement(['Male', 'Female']);

        $m = [['rel' => 'Head', 'age' => $headAge, 'gender' => $headGender, 'family' => true]];

        if ($f->boolean(75)) {
            $m[] = ['rel' => 'Spouse', 'age' => max(20, min(90, $headAge + $f->numberBetween(-6, 4))), 'gender' => $other($headGender), 'family' => true];
        }
        // (a counted loop on purpose: PHP's range(1, 0) counts DOWN and yields two items)
        $maxKids = max(0, min(5, intdiv($headAge - 18, 4)));
        for ($i = 0, $kids = $f->numberBetween(0, $maxKids); $i < $kids; $i++) {
            $m[] = ['rel' => 'Child', 'age' => $f->numberBetween(0, max(0, min(40, $headAge - 20))), 'gender' => $any(), 'family' => true];
        }
        if ($headAge <= 55 && $f->boolean(15)) {
            $m[] = ['rel' => 'Parent', 'age' => min(95, $headAge + $f->numberBetween(20, 30)), 'gender' => $any(), 'family' => false];
        }
        if ($headAge >= 55 && $f->boolean(30)) {
            foreach (range(1, $f->numberBetween(1, 2)) as $i) {
                $m[] = ['rel' => 'Grandchild', 'age' => $f->numberBetween(0, 12), 'gender' => $any(), 'family' => true];
            }
        }
        if ($f->boolean(15)) {
            $m[] = ['rel' => 'Other Relative', 'age' => $f->numberBetween(15, 65), 'gender' => $any(), 'family' => false];
        }
        if ($f->boolean(4)) {
            $m[] = ['rel' => 'Non-relative', 'age' => $f->numberBetween(18, 50), 'gender' => $any(), 'family' => false];
        }

        return $m;
    }

    /** A share of residents move out or die, recorded with a date and a history entry. */
    private function applyLifeEvent(Resident $resident, int $age, ?int $adminId): void
    {
        $roll = $this->faker->randomFloat(3, 0, 1);
        $to = match (true) {
            $roll < ($age >= 70 ? 0.14 : 0.015)        => 'Deceased',
            $roll > 0.93 && $age >= 18                 => 'Transferred',
            default                                    => null,
        };
        if (! $to) {
            return;
        }

        $date = now()->subDays($this->faker->numberBetween(10, 900))->toDateString();
        $movedTo = $to === 'Transferred'
            ? $this->faker->randomElement(['Caloocan City', 'Marikina City', 'Bulacan', 'Cavite', 'Rizal', 'Laguna', 'Pampanga', 'Brgy. Bagong Silangan, Quezon City'])
            : null;

        ResidentStatusLog::create([
            'resident_id'    => $resident->id,
            'from_status'    => 'Active',
            'to_status'      => $to,
            'effective_date' => $date,
            'moved_to'       => $movedTo,
            'changed_by'     => $adminId,
        ]);
        // Assigned directly: status_effective_date is deliberately not mass-assignable
        $resident->residency_status      = $to;
        $resident->status_effective_date = $date;
        $resident->save();
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
