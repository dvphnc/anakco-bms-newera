<?php

namespace Database\Factories;

use App\Models\Purok;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseholdFactory extends Factory
{
    public function definition(): array
    {
        static $sequence = 1;

        $purokId = Purok::inRandomOrder()->first()?->id ?? 1;

        $streets = [
            'Sampaguita St.', 'Rosal St.', 'Ilang-Ilang St.', 'Camia St.',
            'Dahlia St.', 'Gumamela St.', 'Jasmine St.', 'Orchid St.',
            'Maharlika St.', 'Kalayaan St.', 'Mabini St.', 'Rizal St.',
            'Bonifacio St.', 'Aguinaldo St.', 'Luna St.',
        ];

        $filipinoNames = [
            'Juan Santos', 'Maria Reyes', 'Jose Cruz', 'Ana Bautista',
            'Roberto Garcia', 'Luisa Mendoza', 'Ricardo Torres', 'Carmen Flores',
            'Eduardo Gonzales', 'Gloria Ramos', 'Antonio Aquino', 'Teresita Villanueva',
            'Fernando Castro', 'Corazon Pascual', 'Rodrigo Domingo', 'Marilou Valdez',
        ];

        return [
            'household_number'   => 'HH-' . str_pad($sequence++, 4, '0', STR_PAD_LEFT),
            'purok_id'           => $purokId,
            'address'            => $this->faker->buildingNumber() . ' ' .
                                    $this->faker->randomElement($streets) .
                                    ', Barangay New Era, Quezon City',
            'household_head'     => $this->faker->randomElement($filipinoNames),
            'family_size'        => $this->faker->numberBetween(1, 10),
            'is_voter_household' => $this->faker->boolean(70),
        ];
    }
}