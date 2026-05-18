<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blotter_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('complainant_name');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('incident_type');
            $table->date('incident_date');
            $table->string('incident_location');
            $table->text('incident_description');
            $table->string('respondent_name')->nullable();
            $table->enum('status', ['Pending','Under Review','For Mediation','Resolved','Dismissed','Cancelled'])->default('Pending');
            $table->text('notes')->nullable();
            $table->string('processed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_requests');
    }
};
