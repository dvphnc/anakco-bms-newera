<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    public function definition(): array
    {
        static $sequence = 0;

        // On first call, derive starting counter from the highest existing permit_number
        // so re-seeding never collides (format: BIZ-YYYY-NNNNN).
        if ($sequence === 0) {
            $max = \App\Models\Business::max('permit_number');
            if ($max) {
                $parts    = explode('-', $max);
                $sequence = (int) end($parts) + 1;
            } else {
                $sequence = 1;
            }
        }

        $businessTypes = [
            'Sari-Sari Store',
            'Restaurant / Carinderia',
            'Salon / Barbershop',
            'Repair Shop',
            'Pharmacy / Drugstore',
            'Laundry',
            'Printing / Photocopy',
            'Retail Store',
            'Other',
        ];

        $businessPrefixes = ['San', 'Santa', 'New', 'Golden', 'Lucky', 'Star', 'Rose', 'Blessed', 'Royal', 'Metro'];
        $businessSuffixes = ['Store', 'Shop', 'Center', 'Trading', 'Enterprise', 'Services', 'Supply', 'Mart'];

        $filipinoOwnerNames = [
            'Juan Santos', 'Maria Reyes', 'Jose Cruz', 'Ana Bautista', 'Roberto Garcia',
            'Luisa Mendoza', 'Ricardo Torres', 'Carmen Flores', 'Eduardo Gonzales',
            'Gloria Ramos', 'Antonio Aquino', 'Teresita Villanueva', 'Fernando Castro',
            'Corazon Pascual', 'Rodrigo Domingo', 'Marilou Valdez', 'Danilo Navarro',
        ];

        $year = $this->faker->randomElement([2023, 2024, 2025, 2026]);
        $permitNumber = 'BIZ-'.$year.'-'.str_pad($sequence++, 5, '0', STR_PAD_LEFT);
        $permitDate = $this->faker->dateTimeBetween('-3 years', 'now');
        $expiryDate = $this->faker->dateTimeBetween('-1 year', '+2 years');
        $status = now() > $expiryDate
            ? $this->faker->randomElement(['Expired', 'Expired', 'Cancelled'])
            : $this->faker->randomElement(['Active', 'Active', 'Active', 'Suspended']);

        $businessName = $this->faker->randomElement($businessPrefixes).' '.
                        $this->faker->lastName().' '.
                        $this->faker->randomElement($businessSuffixes);

        return [
            'permit_number' => $permitNumber,
            'business_name' => $businessName,
            'business_type' => $this->faker->randomElement($businessTypes),
            'business_address' => $this->faker->buildingNumber().' '.
                                  $this->faker->randomElement(['Sampaguita', 'Rosal', 'Ilang-Ilang', 'Camia']).
                                  ' St., Barangay New Era, Quezon City',
            'owner_name' => $this->faker->randomElement($filipinoOwnerNames),
            'owner_contact' => '09'.$this->faker->numerify('#########'),
            'owner_resident_id' => null,
            'permit_date' => $permitDate,
            'expiry_date' => $expiryDate,
            'status' => $status,
            'issued_by' => 1,
            'created_at' => $permitDate,
            'updated_at' => $permitDate,
        ];
    }
}
