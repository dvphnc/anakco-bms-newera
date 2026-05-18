<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * documents — walk-in and portal-linked records
         *   requestor_name         : who physically collected the document
         *   requestor_relationship : e.g. "Son", "Attorney-in-Fact (SPA)"
         *   requestor_contact      : optional phone/email for pickup notification
         *
         * When no representative is involved these columns mirror the
         * resident's own name (set server-side in DocumentController).
         */
        Schema::table('documents', function (Blueprint $table) {
            $table->string('requestor_name')->nullable()->after('resident_name_portal');
            $table->string('requestor_relationship', 100)->nullable()->after('requestor_name');
            $table->string('requestor_contact', 255)->nullable()->after('requestor_relationship');
        });

        /*
         * document_appointments — scheduling queue (portal + future walk-in pre-reg)
         * Same semantics as above so the Observer can sync the full record.
         */
        Schema::table('document_appointments', function (Blueprint $table) {
            $table->string('requestor_name')->nullable()->after('resident_name');
            $table->string('requestor_relationship', 100)->nullable()->after('requestor_name');
            $table->string('requestor_contact', 255)->nullable()->after('requestor_relationship');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['requestor_name', 'requestor_relationship', 'requestor_contact']);
        });

        Schema::table('document_appointments', function (Blueprint $table) {
            $table->dropColumn(['requestor_name', 'requestor_relationship', 'requestor_contact']);
        });
    }
};
