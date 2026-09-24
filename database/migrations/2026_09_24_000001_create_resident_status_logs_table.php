<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // History of every life-status change (Alive / Deceased / Moved Out),
        // with the date it happened and who recorded it.
        Schema::create('resident_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->restrictOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->date('effective_date');            // date of death / date moved out / date returned
            $table->string('moved_to')->nullable();    // destination, for Moved Out
            $table->text('remarks')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['resident_id', 'created_at']);
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->date('status_effective_date')->nullable()->after('residency_status');
            $table->index('residency_status');         // filtered on every residents-list load
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropIndex(['residency_status']);
            $table->dropColumn('status_effective_date');
        });

        Schema::dropIfExists('resident_status_logs');
    }
};
