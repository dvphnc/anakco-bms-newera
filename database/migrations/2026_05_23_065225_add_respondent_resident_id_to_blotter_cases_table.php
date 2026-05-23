<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->foreignId('respondent_resident_id')->nullable()->after('complainant_resident_id')->constrained('residents')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->dropForeign(['respondent_resident_id']); $table->dropColumn('respondent_resident_id');
        });
    }
};
