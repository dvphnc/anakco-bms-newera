<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This table stores uploaded files per committee
    // (photos, videos, reports, documents, certificates)

    public function up(): void
    {
        Schema::create('committee_records', function (Blueprint $table) {
            $table->id();

            // --- Which committee owns this record ---
            $table->enum('committee_slug', [
                'peace-order',
                'health',
                'education',
                'infrastructure',
                'environment',
                'livelihood',
                'transport',
                'bdrrm',
            ]);

            // --- Record Type ---
            $table->enum('record_type', [
                'Photo',
                'Video',
                'Report',
                'Resolution',
                'Certificate',
                'Partnership',
                'Other',
            ]);

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();        // For uploaded files
            $table->string('file_type')->nullable();        // jpg, pdf, mp4, etc.

            // --- Uploaded By ---
            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_records');
    }
};
