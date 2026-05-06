<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique();      // e.g. "BP-2025-00001"

            // --- Business Info ---
            $table->string('business_name');
            $table->enum('business_type', [
                'Sari-Sari Store',
                'Restaurant / Carinderia',
                'Salon / Barbershop',
                'Repair Shop',
                'Pharmacy / Drugstore',
                'Laundry',
                'Printing / Photocopy',
                'Retail Store',
                'Other',
            ]);
            $table->text('business_address');

            // --- Owner Info ---
            $table->string('owner_name');
            $table->string('owner_contact')->nullable();
            $table->foreignId('owner_resident_id')         // If registered resident
                ->nullable()
                ->constrained('residents')
                ->nullOnDelete();

            // --- Permit Dates ---
            $table->date('permit_date');
            $table->date('expiry_date');

            // --- Status ---
            $table->enum('status', [
                'Active', 'Expired', 'Suspended', 'Cancelled',
            ])->default('Active');

            // --- Issued By ---
            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
