<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make blotter_cases.respondent_name nullable
        //    (portal submissions don't always know the respondent)
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->string('respondent_name')->nullable()->change();
        });

        // 2. Expand businesses.status enum to include portal statuses
        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM(
            'Active','Expired','Suspended','Cancelled','Pending','For Review'
        ) NOT NULL DEFAULT 'Active'");

        // 3. Expand businesses.business_type enum to include online selling
        DB::statement("ALTER TABLE businesses MODIFY COLUMN business_type ENUM(
            'Sari-Sari Store',
            'Restaurant / Carinderia',
            'Salon / Barbershop',
            'Repair Shop',
            'Pharmacy / Drugstore',
            'Laundry',
            'Printing / Photocopy',
            'Retail Store',
            'Online Selling / E-commerce',
            'Other'
        ) NOT NULL");
    }

    public function down(): void
    {
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->string('respondent_name')->nullable(false)->change();
        });

        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM(
            'Active','Expired','Suspended','Cancelled'
        ) NOT NULL DEFAULT 'Active'");

        DB::statement("ALTER TABLE businesses MODIFY COLUMN business_type ENUM(
            'Sari-Sari Store',
            'Restaurant / Carinderia',
            'Salon / Barbershop',
            'Repair Shop',
            'Pharmacy / Drugstore',
            'Laundry',
            'Printing / Photocopy',
            'Retail Store',
            'Other'
        ) NOT NULL");
    }
};
