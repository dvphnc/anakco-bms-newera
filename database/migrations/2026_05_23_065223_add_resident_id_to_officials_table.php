<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('officials', function (Blueprint $table) {
            $table->foreignId('resident_id')->nullable()->after('id')->constrained('residents')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('officials', function (Blueprint $table) {
            $table->dropForeign(['resident_id']); $table->dropColumn('resident_id');
        });
    }
};
