<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Senior citizen status is now worked out from the birthdate (60+), so a
            // stored checkbox can never fall out of date. See Resident::getIsSeniorAttribute().
            $table->dropColumn('is_senior');

            // "Living in Barangay New Era since" — years of residency are calculated
            // from it. Replaces the old Years of Residency number field, which was
            // never saved (there was no column for it).
            $table->date('residing_since')->nullable()->after('address_key');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('residing_since');
            $table->boolean('is_senior')->default(false)->after('is_pwd');
        });

        // Restore the flag from age so rolling back doesn't lose information
        DB::table('residents')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60')
            ->update(['is_senior' => true]);
    }
};
