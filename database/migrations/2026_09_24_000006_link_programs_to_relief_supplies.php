<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Relief programs draw from the BDRRM relief supplies inventory.
 *
 * A program lists what each claim uses (e.g. 1 "Family Food Pack", or
 * 3 kg rice + 5 canned goods), and every change to a supply's quantity is
 * written to a ledger so a voided claim returns exactly what it took.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistance_program_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistance_program_id')->constrained()->cascadeOnDelete();
            // Restrict: a supply that a program uses can't be deleted out from under it
            $table->foreignId('relief_supply_id')->constrained('committee_relief_supplies')->restrictOnDelete();
            $table->unsignedInteger('quantity_per_claim');
            $table->timestamps();

            $table->unique(['assistance_program_id', 'relief_supply_id'], 'program_supply_unique');
        });

        Schema::create('relief_supply_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relief_supply_id')->constrained('committee_relief_supplies')->cascadeOnDelete();
            $table->integer('change');                  // + received / returned, − given out
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->foreignId('resident_transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reason');
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['relief_supply_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relief_supply_movements');
        Schema::dropIfExists('assistance_program_supplies');
    }
};
