<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotter_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();        // e.g. "CASE-2025-0001"

            // --- Incident ---
            $table->enum('incident_type', [
                'Noise Complaint',
                'Physical Assault',
                'Verbal Abuse',
                'Theft',
                'Trespassing',
                'Domestic Dispute',
                'Property Damage',
                'Threat',
                'Other',
            ]);
            $table->date('incident_date');
            $table->string('incident_location');
            $table->text('incident_details');

            // --- Complainant ---
            $table->string('complainant_name');
            $table->string('complainant_address')->nullable();
            $table->string('complainant_contact')->nullable();
            $table->foreignId('complainant_resident_id')    // If registered resident
                ->nullable()
                ->constrained('residents')
                ->nullOnDelete();

            // --- Respondent ---
            $table->string('respondent_name');
            $table->string('respondent_address')->nullable();
            $table->string('respondent_contact')->nullable();

            // --- Case Status ---
            $table->enum('status', [
                'Active',
                'Under Investigation',
                'Mediated',
                'Settled',
                'Closed',
                'Referred to Higher Authority',
            ])->default('Active');

            $table->text('resolution_notes')->nullable();   // What was agreed
            $table->date('settled_at')->nullable();

            // --- Filed By ---
            $table->foreignId('filed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotter_cases');
    }
};
