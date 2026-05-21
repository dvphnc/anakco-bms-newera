<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_appointments', function (Blueprint $table) {
            // Admin-set date when document will be ready for pickup (set when status → Ready)
            $table->date('pickup_date')->nullable()->after('preferred_date');
        });
    }

    public function down(): void
    {
        Schema::table('document_appointments', function (Blueprint $table) {
            $table->dropColumn('pickup_date');
        });
    }
};
