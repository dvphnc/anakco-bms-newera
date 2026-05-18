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
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->string('source')->default('walk-in')->after('case_number');
            $table->string('email')->nullable()->after('complainant_contact');
        });
    }

    public function down(): void
    {
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->dropColumn(['source', 'email']);
        });
    }
};
