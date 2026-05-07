<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peace & Order — Training & Seminar Records
        Schema::create('committee_tanod_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('training_type', ['Training', 'Seminar', 'Workshop', 'Drill', 'Other'])->default('Training');
            $table->date('training_date');
            $table->string('duration')->nullable();           // e.g. "3 days", "8 hours"
            $table->string('venue')->nullable();
            $table->string('facilitator')->nullable();
            $table->integer('participants_count')->default(0);
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();          // certificate / attendance sheet
            $table->timestamps();
        });

        // Health — Clinic Doctors & Staff
        Schema::create('committee_clinic_staff', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->enum('position', ['Doctor', 'Nurse', 'Midwife', 'BHW', 'Dentist', 'Other'])->default('BHW');
            $table->string('specialization')->nullable();
            $table->string('affiliation')->nullable();        // DOH, RHU, private, etc.
            $table->string('contact_number')->nullable();
            $table->string('schedule')->nullable();           // e.g. "Mon–Fri 8am–5pm"
            $table->enum('status', ['Active', 'Inactive', 'On Leave'])->default('Active');
            $table->timestamps();
        });

        // Environment — Street Sweeper Registry
        Schema::create('committee_street_sweepers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('assigned_zone')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('schedule')->nullable();           // e.g. "Mon–Fri 6am–10am"
            $table->date('date_assigned')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'On Leave'])->default('Active');
            $table->timestamps();
        });

        // Infrastructure — Permits & Contracts
        Schema::create('committee_infra_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->nullable();
            $table->string('contractor_name');
            $table->text('scope_of_work')->nullable();
            $table->decimal('contract_amount', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['Pending', 'Active', 'Completed', 'Terminated'])->default('Pending');
            $table->string('file_path')->nullable();          // signed contract document
            $table->timestamps();
        });

        // Infrastructure — Financial Records
        Schema::create('committee_infra_financials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['Budget', 'Utilization', 'Liquidation'])->default('Budget');
            $table->string('fund_source')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->string('reference_number')->nullable();
            $table->text('remarks')->nullable();
            $table->string('file_path')->nullable();          // supporting documents
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_infra_financials');
        Schema::dropIfExists('committee_infra_contracts');
        Schema::dropIfExists('committee_street_sweepers');
        Schema::dropIfExists('committee_clinic_staff');
        Schema::dropIfExists('committee_tanod_trainings');
    }
};
