<?php

namespace Database\Factories;

use App\Models\Household;
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

        $gender = $this->faker->randomElement(['Male', 'Female']);
        $firstName = $gender === 'Male'
            ? $this->faker->randomElement($filipinoFirstNamesMale)
            : $this->faker->randomElement($filipinoFirstNamesFemale);

        $birthdate = $this->faker->dateTimeBetween('-85 years', '-1 year');
        $age = now()->diffInYears($birthdate);

        $purokId = Purok::inRandomOrder()->first()?->id ?? 1;
        $householdId = Household::inRandomOrder()->first()?->id;

        return [
            'first_name' => $firstName,
            'last_name' => $this->faker->randomElement($filipinoLastNames),
            'middle_name' => $this->faker->optional(0.8)->randomElement($filipinoMiddleNames),
            'suffix' => $this->faker->optional(0.1)->randomElement(['Jr.', 'Sr.', 'II', 'III']),
            'birthdate' => $birthdate,
            'gender' => $gender,
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Separated']),
            'birthplace' => $this->faker->randomElement(['Quezon City', 'Manila', 'Caloocan', 'Marikina', 'Pasig', 'Makati', 'Taguig']),
            'nationality' => 'Filipino',
            'religion' => $this->faker->randomElement(['Roman Catholic', 'Iglesia ni Cristo', 'Born Again Christian', 'Islam', 'Protestant']),
            'occupation' => $this->faker->optional(0.7)->randomElement(['Laborer', 'Vendor', 'Driver', 'Teacher', 'Nurse', 'Engineer', 'Student', 'Housewife', 'Retired', 'Self-employed']),
            'contact_number' => '09'.$this->faker->numerify('#########'),
            'email_address' => $this->faker->optional(0.4)->safeEmail(),
            'address' => $this->faker->buildingNumber().' '.
                                  $this->faker->randomElement(['Sampaguita', 'Rosal', 'Ilang-Ilang', 'Camia', 'Dahlia', 'Gumamela']).
                                  ' St., Barangay New Era, Quezon City',
            'purok_id' => $purokId,
            'household_id' => $this->faker->optional(0.7)->passthrough($householdId),
            'residency_status' => $this->faker->randomElement(['Active', 'Active', 'Active', 'Active', 'Deceased', 'Transferred']),
            'is_voter' => $age >= 18 ? $this->faker->boolean(70) : false,
            'is_pwd' => $this->faker->boolean(8),
            'is_senior' => $age >= 60,
            'is_solo_parent' => $this->faker->boolean(5),
            'is_4ps' => $this->faker->boolean(12),
            'photo_path' => null,
        ];
    }
}
