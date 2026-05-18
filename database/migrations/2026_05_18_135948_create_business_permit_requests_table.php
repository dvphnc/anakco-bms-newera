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
        Schema::create('business_permit_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('owner_name');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('business_name');
            $table->string('business_type');
            $table->string('business_address');
            $table->year('operation_year')->nullable();
            $table->text('purpose')->nullable();
            $table->enum('status', ['Pending','Under Review','For Inspection','Approved','Rejected','Cancelled'])->default('Pending');
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
        Schema::dropIfExists('business_permit_requests');
    }
};
