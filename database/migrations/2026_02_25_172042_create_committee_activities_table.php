<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Stores activities AND accomplishments per committee

    public function up(): void
    {
        Schema::create('committee_activities', function (Blueprint $table) {
            $table->id();

            $table->enum('committee_slug', [
                'peace-order',
                'health',
                'education',
                'infrastructure',
                'environment',
                'livelihood',
                'transport',
                'bdrrm'
            ]);

            $table->enum('activity_type', [
                'Activity',
                'Accomplishment'
            ])->default('Activity');

            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date');
            $table->string('location')->nullable();
            $table->integer('participants_count')->default(0);
            $table->enum('status', [
                'Planned', 'Ongoing', 'Completed', 'Cancelled'
            ])->default('Completed');

            // --- Logged By ---
            $table->foreignId('logged_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_activities');
    }
};