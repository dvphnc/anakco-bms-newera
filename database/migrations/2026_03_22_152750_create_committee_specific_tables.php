<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peace & Order — BPSO List
        Schema::create('committee_bpso', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('rank')->nullable();
            $table->string('badge_number')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('assignment')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'On Leave'])->default('Active');
            $table->timestamps();
        });

        // Peace & Order — Patrol Logs
        Schema::create('committee_patrol_logs', function (Blueprint $table) {
            $table->id();
            $table->date('patrol_date');
            $table->string('shift')->nullable();
            $table->string('area_covered');
            $table->integer('personnel_count')->default(0);
            $table->text('findings')->nullable();
            $table->string('reported_by')->nullable();
            $table->timestamps();
        });

        // Health — Patient/Consultation List
        Schema::create('committee_health_records', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('program')->nullable(); // e.g. Vaccination, Prenatal
            $table->date('visit_date');
            $table->string('attended_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Education — Scholarship List
        Schema::create('committee_scholars', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('school');
            $table->string('course_grade_level')->nullable();
            $table->string('scholarship_type')->nullable();
            $table->string('year_level')->nullable();
            $table->decimal('grant_amount', 10, 2)->nullable();
            $table->enum('status', ['Active', 'Graduated', 'Dropped', 'Suspended'])->default('Active');
            $table->date('start_date')->nullable();
            $table->timestamps();
        });

        // Infrastructure — Projects
        Schema::create('committee_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('project_type')->nullable();
            $table->string('location')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('completion_percentage')->default(0);
            $table->enum('status', ['Planned', 'Ongoing', 'Completed', 'On Hold', 'Cancelled'])->default('Planned');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Environment — Programs
        Schema::create('committee_environment_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->string('program_type')->nullable(); // Clean-up, Tree Planting, Waste Mgmt
            $table->date('program_date');
            $table->string('location')->nullable();
            $table->integer('volunteers')->default(0);
            $table->integer('trees_planted')->nullable();
            $table->decimal('waste_collected_kg', 10, 2)->nullable();
            $table->enum('status', ['Planned', 'Completed', 'Cancelled'])->default('Planned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Livelihood — Beneficiaries
        Schema::create('committee_livelihood_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('program_name');
            $table->string('program_type')->nullable(); // Training, Loan, Goods
            $table->date('date_enrolled')->nullable();
            $table->decimal('amount_received', 10, 2)->nullable();
            $table->enum('status', ['Active', 'Completed', 'Dropped'])->default('Active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Transport — TODA / Vehicle Registry
        Schema::create('committee_toda', function (Blueprint $table) {
            $table->id();
            $table->string('operator_name');
            $table->string('driver_name')->nullable();
            $table->string('vehicle_type')->nullable(); // Tricycle, Jeepney
            $table->string('plate_number')->nullable();
            $table->string('toda_name')->nullable();
            $table->string('route')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['Active', 'Expired', 'Suspended'])->default('Active');
            $table->timestamps();
        });

        // BDRRM — Emergency Logs
        Schema::create('committee_emergency_logs', function (Blueprint $table) {
            $table->id();
            $table->string('incident_type'); // Flood, Fire, Earthquake
            $table->date('incident_date');
            $table->string('location');
            $table->integer('affected_families')->default(0);
            $table->integer('affected_persons')->default(0);
            $table->text('description')->nullable();
            $table->text('response_actions')->nullable();
            $table->string('reported_by')->nullable();
            $table->enum('status', ['Active', 'Resolved', 'Monitoring'])->default('Active');
            $table->timestamps();
        });

        // BDRRM — Evacuation Centers
        Schema::create('committee_evacuation_centers', function (Blueprint $table) {
            $table->id();
            $table->string('center_name');
            $table->string('location');
            $table->integer('capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->enum('status', ['Available', 'Active', 'Full', 'Closed'])->default('Available');
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('facilities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_bpso');
        Schema::dropIfExists('committee_patrol_logs');
        Schema::dropIfExists('committee_health_records');
        Schema::dropIfExists('committee_scholars');
        Schema::dropIfExists('committee_projects');
        Schema::dropIfExists('committee_environment_programs');
        Schema::dropIfExists('committee_livelihood_beneficiaries');
        Schema::dropIfExists('committee_toda');
        Schema::dropIfExists('committee_emergency_logs');
        Schema::dropIfExists('committee_evacuation_centers');
    }
};
