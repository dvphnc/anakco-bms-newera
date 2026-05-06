<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            // --- Name ---
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();           // Jr, Sr, III, etc.

            // --- Personal Info ---
            $table->date('birthdate');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('civil_status', [
                'Single', 'Married', 'Widowed', 'Separated', 'Annulled',
            ])->nullable();
            $table->string('birthplace')->nullable();
            $table->string('nationality')->default('Filipino');
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();

            // --- Contact ---
            $table->string('contact_number', 20)->nullable();
            $table->string('email_address')->nullable();

            // --- Address ---
            $table->text('address');
            $table->foreignId('purok_id')
                ->constrained('puroks')
                ->restrictOnDelete();
            $table->foreignId('household_id')
                ->nullable()
                ->constrained('households')
                ->nullOnDelete();

            // --- Classifications ---
            $table->boolean('is_voter')->default(false);
            $table->boolean('is_pwd')->default(false);
            $table->boolean('is_senior')->default(false);
            $table->boolean('is_solo_parent')->default(false);
            $table->boolean('is_4ps')->default(false);      // Pantawid Pamilya

            // --- Status ---
            $table->enum('residency_status', [
                'Active', 'Deceased', 'Transferred',
            ])->default('Active');

            // --- Photo ---
            $table->string('photo_path')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
