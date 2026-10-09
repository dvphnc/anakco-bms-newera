<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Part 3.1: nothing is ever erased. Every app table gets deleted_at (archived when) and
 * deleted_by (archived by whom). Laravel's own tables (sessions, cache, jobs, migrations,
 * password resets) and the program/supply link table are left alone.
 *
 * tests/Feature/ArchiveTest checks that every model's table really has these columns,
 * so a table missing from this list is caught.
 */
return new class extends Migration
{
    private const TABLES = [
        // Residents and households
        'residents', 'households', 'puroks', 'resident_status_logs', 'resident_transactions',
        'religions', 'pabahays', 'pabahay_units',
        // Services
        'documents', 'document_appointments', 'appointment_status_logs',
        'blotter_cases', 'blotter_status_logs', 'businesses', 'business_status_logs',
        'officials', 'assistance_programs',
        // Committees
        'committee_records', 'committee_activities', 'committee_attendances', 'committee_attendance', 'committee_inventories',
        'committee_partnerships', 'committee_medicine_inventory', 'medicine_stock_logs',
        'committee_relief_supplies', 'relief_supply_movements',
        'committee_bpso', 'committee_clinic_staff', 'committee_emergency_logs', 'committee_environment_programs',
        'committee_evacuation_centers', 'committee_health_records', 'committee_infra_contracts', 'committee_infra_financials',
        'committee_projects', 'committee_livelihood_beneficiaries', 'committee_patrol_logs', 'committee_scholars',
        'committee_street_sweepers', 'committee_tanod_trainings', 'committee_toda',
        // System
        'users', 'activity_logs',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $name) {
            if (! Schema::hasTable($name)) {
                continue;
            }
            Schema::table($name, function (Blueprint $table) use ($name) {
                if (! Schema::hasColumn($name, 'deleted_at')) {
                    $table->softDeletes();
                }
                if (! Schema::hasColumn($name, 'deleted_by')) {
                    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $name) {
            if (! Schema::hasTable($name) || ! Schema::hasColumn($name, 'deleted_by')) {
                continue;
            }
            Schema::table($name, function (Blueprint $table) use ($name) {
                $table->dropConstrainedForeignId('deleted_by');
                // residents and assistance_programs had deleted_at before this migration
                if (! in_array($name, ['residents', 'assistance_programs'], true)) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
