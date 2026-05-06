<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('household_number')->unique();    // e.g. "HH-2025-0001"
            $table->foreignId('purok_id')
                ->constrained('puroks')
                ->cascadeOnDelete();
            $table->string('address');
            $table->string('household_head')->nullable();   // Name of head of household
            $table->integer('family_size')->default(1);
            $table->boolean('is_voter_household')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
