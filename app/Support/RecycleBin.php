<?php

namespace App\Support;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Part 3.1: everything that can be archived from the app, and how to show it in the Recycle Bin.
 * Log tables (status logs, stock movements, activity log) are never archived from the app,
 * so they are not listed here.
 */
class RecycleBin
{
    /** key => [label, model, icon] */
    public const TYPES = [
        'resident'     => ['Resident', Models\Resident::class, 'fa-user'],
        'household'    => ['Household', Models\Household::class, 'fa-house'],
        'document'     => ['Document', Models\Document::class, 'fa-file-lines'],
        'appointment'  => ['Appointment', Models\DocumentAppointment::class, 'fa-calendar-check'],
        'blotter'      => ['Blotter case', Models\BlotterCase::class, 'fa-gavel'],
        'business'     => ['Business permit', Models\Business::class, 'fa-store'],
        'official'     => ['Official', Models\Official::class, 'fa-user-tie'],
        'program'      => ['Assistance program', Models\AssistanceProgram::class, 'fa-hand-holding-heart'],
        'user'         => ['User account', Models\User::class, 'fa-user-shield'],
        'role'         => ['Role', Models\Role::class, 'fa-key'],
        // Committees
        'committee-record'     => ['Committee record', Models\CommitteeRecord::class, 'fa-folder'],
        'committee-activity'   => ['Committee activity', Models\CommitteeActivity::class, 'fa-calendar-day'],
        'committee-attendance' => ['Attendance record', Models\CommitteeAttendance::class, 'fa-clipboard-user'],
        'committee-inventory'  => ['Inventory item', Models\CommitteeInventory::class, 'fa-boxes-stacked'],
        'partnership'          => ['Partnership', Models\CommitteePartnership::class, 'fa-handshake'],
        'medicine'             => ['Medicine', Models\MedicineInventory::class, 'fa-pills'],
        'relief-supply'        => ['Relief supply', Models\ReliefSupply::class, 'fa-box-open'],
        'bpso'                 => ['BPSO member', Models\BpsoMember::class, 'fa-shield-halved'],
        'patrol'               => ['Patrol log', Models\PatrolLog::class, 'fa-person-walking'],
        'training'             => ['Tanod training', Models\TanodTraining::class, 'fa-chalkboard-user'],
        'health'               => ['Health record', Models\HealthRecord::class, 'fa-notes-medical'],
        'clinic'               => ['Clinic staff', Models\ClinicStaff::class, 'fa-user-nurse'],
        'scholar'              => ['Scholar', Models\Scholar::class, 'fa-graduation-cap'],
        'project'              => ['Infrastructure project', Models\InfraProject::class, 'fa-helmet-safety'],
        'contract'             => ['Contract', Models\InfraContract::class, 'fa-file-signature'],
        'financial'            => ['Financial record', Models\InfraFinancial::class, 'fa-peso-sign'],
        'environment'          => ['Environment program', Models\EnvironmentProgram::class, 'fa-leaf'],
        'sweeper'              => ['Street sweeper', Models\StreetSweeper::class, 'fa-broom'],
        'beneficiary'          => ['Livelihood beneficiary', Models\LivelihoodBeneficiary::class, 'fa-briefcase'],
        'toda'                 => ['TODA vehicle', Models\TodaVehicle::class, 'fa-motorcycle'],
        'emergency'            => ['Emergency log', Models\EmergencyLog::class, 'fa-triangle-exclamation'],
        'evacuation'           => ['Evacuation center', Models\EvacuationCenter::class, 'fa-house-flood-water'],
    ];

    /** Attribute names tried, in order, to name a record in the list */
    private const NAME_FIELDS = [
        'household_number', 'doc_number', 'appointment_number', 'case_number', 'permit_number', 'contract_number',
        'title', 'name', 'item_name', 'medicine_name', 'partner_name', 'event_name', 'project_name',
        'program_name', 'center_name', 'operator_name', 'patient_name', 'incident_type', 'area_covered',
    ];

    public static function has(string $type): bool
    {
        return isset(self::TYPES[$type]);
    }

    public static function modelFor(string $type): string
    {
        return self::TYPES[$type][1];
    }

    /** Archived records, newest first, as plain rows for the page. */
    public static function items(?string $type = null, ?string $search = null, int $perType = 200): Collection
    {
        $types = $type && self::has($type) ? [$type => self::TYPES[$type]] : self::TYPES;

        return collect($types)
            ->flatMap(function ($meta, $key) use ($perType) {
                [$label, $class, $icon] = $meta;

                return $class::onlyTrashed()->with('archivedBy')->latest('deleted_at')->limit($perType)->get()
                    ->map(fn (Model $m) => (object) [
                        'type'        => $key,
                        'label'       => $label,
                        'icon'        => $icon,
                        'id'          => $m->getKey(),
                        'name'        => self::nameOf($m),
                        'detail'      => self::detailOf($m),
                        'archived_at' => $m->deleted_at,
                        'archived_by' => $m->archivedBy?->name,
                    ]);
            })
            ->when($search, fn ($c) => $c->filter(fn ($row) => str_contains(mb_strtolower($row->name.' '.$row->detail), mb_strtolower($search))))
            ->sortByDesc('archived_at')
            ->values();
    }

    public static function counts(): array
    {
        return collect(self::TYPES)->map(fn ($meta) => $meta[1]::onlyTrashed()->count())->filter()->all();
    }

    public static function nameOf(Model $m): string
    {
        if (isset($m->full_name) && $m->full_name) {
            return $m->full_name;
        }
        foreach (self::NAME_FIELDS as $field) {
            if (filled($m->getAttribute($field))) {
                return (string) $m->getAttribute($field);
            }
        }

        return class_basename($m).' #'.$m->getKey();
    }

    private static function detailOf(Model $m): string
    {
        return match (true) {
            $m instanceof Models\Household => (string) $m->address,
            $m instanceof Models\Document  => trim($m->document_type.' · '.($m->resident?->full_name ?? '')),
            $m instanceof Models\Business  => (string) $m->business_name,
            $m instanceof Models\DocumentAppointment => trim($m->document_type.' · '.$m->resident_name),
            $m instanceof Models\BlotterCase => (string) $m->incident_type,
            $m instanceof Models\InfraContract => (string) $m->contractor_name,
            $m instanceof Models\PatrolLog => (string) $m->patrol_date?->format('M d, Y'),
            $m instanceof Models\User      => (string) $m->email,
            $m instanceof Models\Resident  => (string) $m->address,
            default                        => '',
        };
    }
}
