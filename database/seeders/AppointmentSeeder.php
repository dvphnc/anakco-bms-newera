<?php

namespace Database\Seeders;

use App\Models\DocumentAppointment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * AppointmentSeeder
 * ──────────────────────────────────────────────────────────────────────────
 * Seeds realistic portal appointment records so the Appointments admin page
 * looks active during a capstone / portfolio demo.
 *
 * Generates 50 appointments spread across the last 90 days with a realistic
 * mix of statuses:
 *   Pending (12) · Confirmed (10) · Processing (8) · Ready (5)
 *   Released (10) · Cancelled (5)
 *
 * All inserts skip duplicates based on appointment_number uniqueness.
 * ──────────────────────────────────────────────────────────────────────────
 */
class AppointmentSeeder extends Seeder
{
    // Filipino names pool for demo data
    private const RESIDENTS = [
        'Juan Santos',         'Maria Reyes',         'Jose Cruz',
        'Ana Bautista',        'Roberto Garcia',      'Luisa Mendoza',
        'Ricardo Torres',      'Carmen Flores',       'Eduardo Gonzales',
        'Gloria Ramos',        'Antonio Aquino',      'Teresita Villanueva',
        'Fernando Castro',     'Corazon Pascual',     'Rodrigo Domingo',
        'Marilou Valdez',      'Danilo Navarro',      'Erlinda Morales',
        'Ernesto Aguilar',     'Rowena Salazar',      'Bernardo Dela Cruz',
        'Maricel Abad',        'Rodrigo Buenaventura','Liza Macapagal',
        'Ramon Tolentino',     'Cynthia Mercado',     'Felix Lim',
        'Esperanza Ocampo',    'Edilberto Villafuerte','Rosario Enriquez',
    ];

    private const PURPOSES = [
        'For employment application',
        'For business permit renewal',
        'For bank loan application',
        'For school scholarship application',
        'For PhilHealth enrollment',
        'For SSS membership purposes',
        'For court requirement',
        'For travel abroad (OFW)',
        'For government ID application',
        'For DSWD welfare assistance',
        'For housing loan application',
        'Personal use / general purposes',
    ];

    public function run(): void
    {
        $appointments = $this->buildAppointments();

        foreach ($appointments as $data) {
            // Skip if appointment_number already exists
            if (DocumentAppointment::where('appointment_number', $data['appointment_number'])->exists()) {
                continue;
            }
            DocumentAppointment::create($data);
        }

        $this->command->info('✅  AppointmentSeeder: ' . count($appointments) . ' portal appointments seeded.');
    }

    private function buildAppointments(): array
    {
        $now        = Carbon::now();
        $records    = [];
        $sequence   = 1;

        // Define the mix: [status => count]
        $statusMix = [
            'Pending'    => 12,
            'Confirmed'  => 10,
            'Processing' => 8,
            'Ready'      => 5,
            'Released'   => 10,
            'Cancelled'  => 5,
        ];

        foreach ($statusMix as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $resident   = self::RESIDENTS[array_rand(self::RESIDENTS)];
                $docType    = DocumentAppointment::$documentTypes[array_rand(DocumentAppointment::$documentTypes)];
                $purpose    = self::PURPOSES[array_rand(self::PURPOSES)];

                // Stagger creation dates: recent statuses are newer
                $daysAgo    = match ($status) {
                    'Pending'    => rand(0, 7),
                    'Confirmed'  => rand(3, 14),
                    'Processing' => rand(5, 20),
                    'Ready'      => rand(7, 25),
                    'Released'   => rand(14, 60),
                    'Cancelled'  => rand(10, 90),
                    default      => rand(0, 30),
                };

                $createdAt  = $now->copy()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                // Preferred date is 2–7 business days after request
                $preferredDate = $createdAt->copy()->addDays(rand(2, 7));
                // Clamp future dates to max 30 days from now
                if ($preferredDate->isAfter($now->copy()->addDays(30))) {
                    $preferredDate = $now->copy()->addDays(rand(3, 10));
                }

                // Build appointment number: APT-YYYYMMDD-XXXX
                $apptDate  = $createdAt->format('Ymd');
                $apptCode  = strtoupper(Str::random(4));
                $apptNum   = "APT-{$apptDate}-{$apptCode}";

                $releasedAt = null;
                if (in_array($status, ['Released'])) {
                    $releasedAt = $createdAt->copy()->addDays(rand(1, 5));
                }

                $records[] = [
                    'appointment_number'       => $apptNum,
                    'source'                   => 'portal',
                    'resident_name'            => $resident,
                    'requestor_name'           => null,
                    'requestor_relationship'   => null,
                    'requestor_contact'        => null,
                    'contact_number'           => '09' . rand(100000000, 999999999),
                    'email'                    => strtolower(str_replace(' ', '.', $resident)) . '@gmail.com',
                    'document_type'            => $docType,
                    'purpose'                  => $purpose,
                    'preferred_date'           => $preferredDate->toDateString(),
                    'status'                   => $status,
                    'notes'                    => $this->pickNotes($status),
                    'processed_by'             => in_array($status, ['Processing', 'Ready', 'Released']) ? 1 : null,
                    'released_at'              => $releasedAt,
                    'created_at'               => $createdAt,
                    'updated_at'               => $createdAt->copy()->addHours(rand(0, 4)),
                ];

                $sequence++;
            }
        }

        return $records;
    }

    private function pickNotes(string $status): ?string
    {
        return match ($status) {
            'Confirmed'  => 'Appointment confirmed. Please bring valid ID and ₱50 documentary stamp fee.',
            'Processing' => 'Document is currently being prepared by the barangay secretary.',
            'Ready'      => 'Document is ready for pick-up. Office hours: 8AM–5PM, Monday–Friday.',
            'Released'   => 'Document successfully released to the requesting party.',
            'Cancelled'  => 'Request cancelled by applicant. No further action required.',
            default      => null,
        };
    }
}
