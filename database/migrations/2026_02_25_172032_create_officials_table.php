<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officials', function (Blueprint $table) {
            $table->id();

            // --- Personal Info ---
            $table->string('full_name');
            $table->string('position');                     // e.g. "Punong Barangay"
            $table->string('committee')->nullable();        // e.g. "Peace & Order"
            $table->string('contact_number')->nullable();
            $table->string('photo_path')->nullable();

            // --- Term ---
            $table->date('term_start');
            $table->date('term_end');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officials');
    }
};
