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
            // 'portal' = Resident Portal submission, 'walk-in' = staff-created
            $table->string('source')->default('portal')->after('appointment_number');
        });
    }

    public function down(): void
    {
        Schema::table('document_appointments', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
