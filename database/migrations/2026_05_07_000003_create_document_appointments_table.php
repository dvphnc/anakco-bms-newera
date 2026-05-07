<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            $table->string('resident_name');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('document_type');
            $table->text('purpose')->nullable();
            $table->date('preferred_date');
            $table->enum('status', ['Pending', 'Confirmed', 'Processing', 'Ready', 'Released', 'Cancelled'])
                  ->default('Pending');
            $table->text('notes')->nullable();           // staff notes
            $table->string('processed_by')->nullable();  // staff name who last updated
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_appointments');
    }
};
