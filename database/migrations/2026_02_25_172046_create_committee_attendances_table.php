<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_attendances', function (Blueprint $table) {
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

            $table->string('event_name');                   // e.g. "Monthly Meeting"
            $table->date('event_date');
            $table->string('venue')->nullable();
            $table->integer('total_attendees')->default(0);
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();        // Scanned attendance sheet

            $table->foreignId('recorded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_attendances');
    }
};