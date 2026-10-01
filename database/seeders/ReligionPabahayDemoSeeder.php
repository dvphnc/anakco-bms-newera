<?php

namespace Database\Seeders;

use App\Models\Pabahay;
use App\Models\PabahayUnit;
use App\Models\Purok;
use App\Models\Religion;
use App\Models\Resident;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Practice data for Part 2: three Pabahay blocks with units, and some INC households
 * living in them as Families of Ministers.
 *
 * Safe to run on top of existing practice data (it only adds Pabahay rows and flags
 * residents), and safe to run twice (it only fills units that are still empty).
 * Called by FakeDataSeeder, or run on its own:
 *     php artisan db:seed --class=ReligionPabahayDemoSeeder
 *
 * Note: the families keep their existing street address; only the Pabahay unit is set.
 */
class ReligionPabahayDemoSeeder extends Seeder
{
    // name => number of units
    private const BLOCKS = ['Pabahay A' => 10, 'Pabahay B' => 8, 'Pabahay C' => 6];

    // Leave a few units empty so the demo shows vacancies
    private const VACANT = 4;

    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->error('ReligionPabahayDemoSeeder refused to run: APP_ENV is "production".');

            return;
        }

        $incId = Religion::where('is_inc', true)->value('id');
        if (! $incId) {
            $this->command?->error('The religion list is empty. Run the migrations first.');

            return;
        }

        $puroks = Purok::orderBy('id')->get()->values();
        $i = 0;
        foreach (self::BLOCKS as $name => $unitCount) {
            $purok = $puroks->isNotEmpty() ? $puroks[$i % $puroks->count()] : null;
            $pabahay = Pabahay::firstOrCreate(['name' => $name], [
                'location' => "Ministers' housing, ".($purok?->name ?? 'Barangay New Era'),
                'purok_id' => $purok?->id,
            ]);
            $letter = substr($name, -1);
            for ($n = 1; $n <= $unitCount; $n++) {
                $pabahay->units()->firstOrCreate(['unit_no' => "$letter-$n"]);
            }
            $i++;
        }

        // Empty, active units, in order
        $free = PabahayUnit::with('pabahay')->where('is_active', true)->whereDoesntHave('residents')->ordered()->get()
            ->sortBy(fn ($u) => $u->pabahay->name)->values();
        $needed = max(0, $free->count() - self::VACANT);
        if ($needed === 0) {
            $this->command?->info('Pabahay units already filled. Nothing to add.');

            return;
        }

        // INC households of 3 to 6 living members that are not ministers' families yet
        $households = Resident::query()
            ->where('religion_id', $incId)
            ->where('residency_status', 'Active')
            ->whereNotNull('household_id')
            ->where('is_minister_family', false)
            ->select('household_id', DB::raw('COUNT(*) AS members'))
            ->groupBy('household_id')
            ->havingRaw('COUNT(*) BETWEEN 3 AND 6')
            ->inRandomOrder()
            ->limit($needed)
            ->pluck('household_id');

        foreach ($households as $k => $householdId) {
            // Mass update on purpose: the religion is already INC, no need to run the model rules per person
            Resident::where('household_id', $householdId)
                ->where('residency_status', 'Active')
                ->update(['is_minister_family' => true, 'pabahay_unit_id' => $free[$k]->id]);
        }

        $blocks = count(self::BLOCKS);
        $this->command?->info("Pabahay practice data: {$households->count()} ministers' families placed in {$blocks} Pabahay blocks.");
    }
}
