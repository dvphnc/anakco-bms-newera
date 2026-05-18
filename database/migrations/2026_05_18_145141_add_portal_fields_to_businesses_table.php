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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('source')->default('walk-in')->after('permit_number');
            $table->string('email')->nullable()->after('owner_contact');
            $table->date('permit_date')->nullable()->change();
            $table->date('expiry_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['source', 'email']);
            $table->date('permit_date')->nullable(false)->change();
            $table->date('expiry_date')->nullable(false)->change();
        });
    }
};
