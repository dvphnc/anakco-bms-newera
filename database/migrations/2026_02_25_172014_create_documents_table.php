<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number')->unique();         // e.g. "DOC-2025-00001"

            // --- Who requested it ---
            $table->foreignId('resident_id')
                ->constrained('residents')
                ->cascadeOnDelete();

            // --- Document Details ---
            $table->enum('document_type', [
                'Barangay Clearance',
                'Certificate of Residency',
                'Certificate of Indigency',
                'Good Moral Character',
                'Business Clearance',
                'Certificate of Live Birth',
                'Other',
            ]);
            $table->text('purpose');                        // Why they need it
            $table->decimal('fee_paid', 8, 2)->default(0.00);

            // --- Status ---
            $table->enum('status', [
                'Pending', 'Processing', 'Released', 'Cancelled',
            ])->default('Pending');

            // --- Who processed it ---
            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
