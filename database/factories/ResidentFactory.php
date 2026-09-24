<?php

namespace Database\Factories;

use App\Models\Purok;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidentFactory extends Factory
{
    public function definition(): array
    {
        $filipinoFirstNamesMale = [
            'Juan', 'Jose', 'Miguel', 'Antonio', 'Roberto', 'Eduardo', 'Ricardo', 'Fernando',
            'Rodrigo', 'Danilo', 'Ernesto', 'Alfredo', 'Ramon', 'Rolando', 'Arnel', 'Rommel',
            'Jayson', 'Mark', 'Christian', 'John', 'Ryan', 'Kevin', 'Bryan', 'Carlo',
            'Jerome', 'Aldrin', 'Rodel', 'Gilbert', 'Noel', 'Dennis', 'Renato', 'Wilfredo',
            'Bonifacio', 'Andres', 'Rafael', 'Lorenzo', 'Gregorio', 'Teodoro', 'Isidro', 'Crisanto',
        ];

        $filipinoFirstNamesFemale = [
            'Maria', 'Ana', 'Rosa', 'Luisa', 'Carmen', 'Gloria', 'Teresita', 'Lourdes',
            'Corazon', 'Marilou', 'Nenita', 'Esther', 'Rosario', 'Remedios', 'Erlinda',
            'Maricel', 'Jennifer', 'Christine', 'Angela', 'Patricia', 'Kristine', 'Joanna',
            'Melissa', 'Michelle', 'Sheila', 'Rowena', 'Maribel', 'Ligaya', 'Felicitas',
            'Consolacion', 'Milagros', 'Adoracion', 'Purificacion', 'Resurreccion', 'Natividad',
        ];

        $filipinoLastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres',
            'Flores', 'Gonzales', 'Ramos', 'Aquino', 'Dela Cruz', 'Villanueva', 'Castro',
            'Pascual', 'Domingo', 'Magno', 'Valdez', 'Navarro', 'Morales', 'Aguilar',
            'Salazar', 'Hernandez', 'Mercado', 'Dizon', 'Manalo', 'Lim', 'Tan', 'Co',
            'Sy', 'Ong', 'Chua', 'Buenaventura', 'Macaraeg', 'Tolentino', 'Bernardo',
            'Dela Torre', 'San Juan', 'Del Rosario', 'Punzalan', 'Lacson', 'Macapagal',
        ];

        $filipinoMiddleNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia', 'Mendoza', 'Torres', 'Flores',
            'Gonzales', 'Ramos', 'Aquino', 'Villanueva', 'Castro', 'Pascual', 'Domingo',
            'Valdez', 'Navarro', 'Morales', 'Aguilar', 'Salazar', 'Hernandez', 'Mercado',
        ];

        // Attributes that depend on other attributes are closures: Laravel resolves them
        // after any overrides, so e.g. factory()->create(['gender' => 'Female']) also
        // gets a female first name, and an overridden birthdate drives the voter /
        // senior flags. (The old version computed age with now()->diffInYears($past),
        // which is NEGATIVE in Carbon 3 — so almost nobody was ever a voter or senior.)
        $ageOf = fn (array $a) => \Carbon\Carbon::parse($a['birthdate'])->age;

        return [
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'first_name' => fn (array $a) => $this->faker->randomElement(
                $a['gender'] === 'Male' ? $filipinoFirstNamesMale : $filipinoFirstNamesFemale
            ),
            'last_name' => $this->faker->randomElement($filipinoLastNames),
            'middle_name' => $this->faker->optional(0.8)->randomElement($filipinoMiddleNames),
            'suffix' => fn (array $a) => $a['gender'] === 'Male'
                ? $this->faker->optional(0.1)->randomElement(['Jr.', 'Sr.', 'II', 'III'])
                : null,
            'birthdate' => $this->faker->dateTimeBetween('-85 years', '-1 year')->format('Y-m-d'),
            'civil_status' => fn (array $a) => $ageOf($a) < 18
                ? 'Single'
                : $this->faker->randomElement(['Single', 'Married', 'Married', 'Widowed', 'Separated']),
            'birthplace' => $this->faker->randomElement(['Quezon City', 'Manila', 'Caloocan', 'Marikina', 'Pasig', 'Makati', 'Taguig']),
            'nationality' => 'Filipino',
            'religion' => $this->faker->randomElement(['Roman Catholic', 'Iglesia ni Cristo', 'Born Again Christian', 'Islam', 'Protestant']),
            'occupation' => fn (array $a) => $ageOf($a) < 18
                ? ($ageOf($a) >= 5 ? 'Student' : null)
                : $this->faker->optional(0.7)->randomElement(['Laborer', 'Vendor', 'Driver', 'Teacher', 'Nurse', 'Engineer', 'Housewife', 'Retired', 'Self-employed']),
            'contact_number' => '09'.$this->faker->numerify('#########'),
            'email_address' => $this->faker->optional(0.4)->safeEmail(),
            'address' => $this->faker->buildingNumber().' '.
                                  $this->faker->randomElement(['Sampaguita', 'Rosal', 'Ilang-Ilang', 'Camia', 'Dahlia', 'Gumamela']).
                                  ' St., Barangay New Era, Quezon City',
            // Lazy: only queried when the caller doesn't pass a purok_id
            'purok_id' => fn () => Purok::inRandomOrder()->value('id') ?? 1,
            // Households are formed by address (HouseholdGroupingService), never picked at random
            'household_id' => null,
            'residency_status' => $this->faker->randomElement(array_merge(array_fill(0, 86, 'Active'), array_fill(0, 8, 'Transferred'), array_fill(0, 6, 'Deceased'))),
            'is_voter' => fn (array $a) => $ageOf($a) >= 18 && $this->faker->boolean(75),
            'precinct_no' => fn (array $a) => $a['is_voter'] ? $this->faker->numerify('0###').$this->faker->randomElement(['A', 'B', 'C']) : null,
            'is_pwd' => $this->faker->boolean(8),
            // (senior status is calculated from birthdate — no stored flag)
            // About half were born here; the rest moved in within the last 40 years
            'residing_since' => function (array $a) {
                $birth = \Carbon\Carbon::parse($a['birthdate']);
                $from  = $birth->max(now()->subYears(40));
                if ($this->faker->boolean(50) || $from->gte(now()->subMonth())) {
                    return $birth->toDateString();
                }

                return $this->faker->dateTimeBetween($from, '-1 month')->format('Y-m-d');
            },
            'is_solo_parent' => fn (array $a) => $ageOf($a) >= 18 && $this->faker->boolean(5),
            'is_4ps' => $this->faker->boolean(12),
            'photo_path' => null,
        ];
    }
}
