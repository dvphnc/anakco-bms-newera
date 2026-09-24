<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A distribution / assistance program and its claiming rule,
        // e.g. "Relief Pack — Typhoon Kristine": one claim per household.
        Schema::create('assistance_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['relief', 'medicine', 'financial', 'livelihood', 'other']);
            $table->enum('claim_scope', ['household', 'resident'])->default('household');
            $table->unsignedSmallInteger('max_claims')->default(1);   // per household / resident, within the period
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        // Everything a resident has received from the barangay. Never deleted —
        // a mistaken entry is voided (who, when, why), which also frees its claim.
        Schema::create('resident_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable()->unique();          // TXN-2026-000123 (set right after insert)
            $table->foreignId('resident_id')->constrained()->restrictOnDelete();
            $table->foreignId('household_id')->nullable()->constrained()->nullOnDelete(); // household at the time
            $table->foreignId('assistance_program_id')->nullable()->constrained()->restrictOnDelete();
            $table->enum('type', ['document', 'medicine', 'relief', 'financial', 'livelihood', 'other']);
            $table->nullableMorphs('source');                              // e.g. the Document or MedicineStockLog
            $table->string('description');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit', 30)->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->dateTime('transacted_at');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            // "{program}:H{household}" or "{program}:R{resident}" for one-claim programs.
            // Unique, so two PCs can't record the same claim at the same moment.
            // Set to NULL when voided, which frees the claim again.
            $table->string('claim_lock')->nullable()->unique();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('void_reason')->nullable();
            $table->timestamps();

            $table->index(['resident_id', 'transacted_at']);
            $table->index(['assistance_program_id', 'household_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_transactions');
        Schema::dropIfExists('assistance_programs');
    }
};
