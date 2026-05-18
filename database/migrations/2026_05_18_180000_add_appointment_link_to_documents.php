<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Convert document_type + status from ENUM → VARCHAR so that:
         *  - document_type accepts the same values the portal form uses
         *    (e.g. "Certificate of Good Moral Character", "Barangay ID")
         *    without an ALTER ENUM each time a new type is added.
         *  - status accepts the full appointment lifecycle
         *    (Pending · Confirmed · Processing · Ready · Released · Cancelled)
         *    enabling 1-to-1 sync with document_appointments.
         */
        DB::statement("ALTER TABLE documents MODIFY COLUMN document_type VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE documents MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'Pending'");

        /*
         * Make resident_id nullable — portal submissions have no registered
         * resident account; the applicant's name is stored in resident_name_portal.
         */
        DB::statement("ALTER TABLE documents MODIFY COLUMN resident_id BIGINT UNSIGNED NULL");

        Schema::table('documents', function (Blueprint $table) {
            // FK link to the scheduling record (null for walk-in documents)
            $table->unsignedBigInteger('appointment_id')
                  ->nullable()
                  ->after('id');

            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('document_appointments')
                  ->nullOnDelete();

            // 'portal' | 'walk-in'
            $table->string('source', 20)
                  ->default('walk-in')
                  ->after('appointment_id');

            // Portal applicant name when resident_id is null
            $table->string('resident_name_portal')
                  ->nullable()
                  ->after('source')
                  ->comment('Applicant name for portal submissions without a resident account');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn(['appointment_id', 'source', 'resident_name_portal']);
        });

        DB::statement("ALTER TABLE documents MODIFY COLUMN resident_id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE documents MODIFY COLUMN document_type ENUM('Barangay Clearance','Certificate of Residency','Certificate of Indigency','Good Moral Character','Business Clearance','Certificate of Live Birth','Other') NOT NULL");
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('Pending','Processing','Released','Cancelled') NOT NULL DEFAULT 'Pending'");
    }
};
