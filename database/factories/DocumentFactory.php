<?php

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        static $sequence = 1;

        $residentId = Resident::inRandomOrder()->first()?->id ?? 1;
        $year = $this->faker->randomElement([2024, 2025, 2026]);
        $docNumber = 'DOC-'.$year.'-'.str_pad($sequence++, 5, '0', STR_PAD_LEFT);

        $docType = $this->faker->randomElement([
            'Barangay Clearance',
            'Certificate of Indigency',
            'Certificate of Residency',
            'Business Clearance',
            'Good Moral Character',
            'Certificate of Live Birth',
            'Other',
        ]);

        $purposes = [
            'Barangay Clearance' => ['Employment', 'Bank requirements', 'Loan application', 'School enrollment', 'NBI Clearance requirement'],
            'Certificate of Indigency' => ['Medical assistance', 'Scholarship application', 'Government assistance', 'Hospital admission'],
            'Certificate of Residency' => ['School enrollment', 'Government transaction', 'Passport application', 'Voter registration'],
            'Business Clearance' => ['Business permit renewal', 'New business registration', 'BIR registration'],
            'Good Moral Character' => ['Employment', 'School requirement', 'Scholarship application'],
            'Certificate of Live Birth' => ['PSA requirement', 'School enrollment', 'Government transaction'],
            'Other' => ['General purpose', 'Personal use', 'Government transaction'],
        ];

        $status = $this->faker->randomElement(['Pending', 'Pending', 'Processing', 'Released', 'Released', 'Released']);
        $createdAt = $this->faker->dateTimeBetween('-2 years', 'now');

        return [
            'doc_number' => $docNumber,
            'resident_id' => $residentId,
            'document_type' => $docType,
            'purpose' => $this->faker->randomElement($purposes[$docType]),
            'fee_paid' => $this->faker->randomElement([0, 50, 100, 150, 200]),
            'status' => $status,
            'issued_by' => 1,
            'released_at' => $status === 'Released' ? $this->faker->dateTimeBetween($createdAt, 'now') : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
