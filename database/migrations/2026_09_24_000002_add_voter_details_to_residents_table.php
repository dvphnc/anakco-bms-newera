<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('precinct_no', 20)->nullable()->after('is_voter');
            $table->string('voters_id_no', 30)->nullable()->after('precinct_no');
            // "Registered voters currently living here" = is_voter + Alive
            $table->index(['is_voter', 'residency_status']);
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropIndex(['is_voter', 'residency_status']);
            $table->dropColumn(['precinct_no', 'voters_id_no']);
        });
    }
};
