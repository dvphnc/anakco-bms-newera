<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlotterCaseFactory extends Factory
{
    public function definition(): array
    {
        static $sequence = 1;

        $filipinoNames = [
            'Juan Santos', 'Maria Reyes', 'Jose Cruz', 'Ana Bautista', 'Roberto Garcia',
            'Luisa Mendoza', 'Ricardo Torres', 'Carmen Flores', 'Eduardo Gonzales',
            'Gloria Ramos', 'Antonio Aquino', 'Teresita Villanueva', 'Fernando Castro',
            'Corazon Pascual', 'Rodrigo Domingo', 'Marilou Valdez', 'Danilo Navarro',
            'Erlinda Morales', 'Ernesto Aguilar', 'Rowena Salazar',
        ];

        $incidentTypes = [
            'Noise Complaint', 'Physical Assault', 'Verbal Abuse', 'Theft',
            'Trespassing', 'Domestic Dispute', 'Property Damage', 'Threat', 'Other',
        ];

        $locations = [
            'Sampaguita St., Barangay New Era',
            'Rosal St., Barangay New Era',
            'Ilang-Ilang St., Barangay New Era',
            'Purok 1, Barangay New Era',
            'Purok 3, Barangay New Era',
            'Near the Barangay Hall',
            'Basketball Court, Barangay New Era',
            'Kalayaan St., Barangay New Era',
        ];

        $year = $this->faker->randomElement([2024, 2025, 2026]);
        $caseNumber = 'BLT-'.$year.'-'.str_pad($sequence++, 5, '0', STR_PAD_LEFT);
        $status = $this->faker->randomElement([
            'Active', 'Active', 'Under Investigation', 'Mediated', 'Settled', 'Settled', 'Closed',
        ]);
        $incidentDate = $this->faker->dateTimeBetween('-2 years', 'now');

        return [
            'case_number' => $caseNumber,
            'incident_type' => $this->faker->randomElement($incidentTypes),
            'incident_date' => $incidentDate,
            'incident_location' => $this->faker->randomElement($locations),
            'incident_details' => $this->faker->paragraph(3),
            'complainant_name' => $this->faker->randomElement($filipinoNames),
            'complainant_address' => $this->faker->buildingNumber().' Sampaguita St., Barangay New Era',
            'complainant_contact' => '09'.$this->faker->numerify('#########'),
            'complainant_resident_id' => null,
            'respondent_name' => $this->faker->randomElement($filipinoNames),
            'respondent_address' => $this->faker->buildingNumber().' Rosal St., Barangay New Era',
            'respondent_contact' => '09'.$this->faker->numerify('#########'),
            'status' => $status,
            'resolution_notes' => in_array($status, ['Settled', 'Closed', 'Mediated'])
                                        ? $this->faker->paragraph(2)
                                        : null,
            'settled_at' => in_array($status, ['Settled', 'Closed'])
                                        ? $this->faker->dateTimeBetween($incidentDate, 'now')
                                        : null,
            'filed_by' => 1,
            'created_at' => $incidentDate,
            'updated_at' => $incidentDate,
        ];
    }
}
