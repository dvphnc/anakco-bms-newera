<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('medicine_stock_logs', function (Blueprint $table) {
            $table->string('beneficiary_name')->nullable()->after('reason');
            $table->foreignId('beneficiary_resident_id')->nullable()->after('beneficiary_name')->constrained('residents')->nullOnDelete();
            $table->string('purpose')->nullable()->after('beneficiary_resident_id');
        });
    }
    public function down(): void {
        Schema::table('medicine_stock_logs', function (Blueprint $table) {
            $table->dropForeign(['beneficiary_resident_id']); $table->dropColumn(['beneficiary_name','beneficiary_resident_id','purpose']);
        });
    }
};
