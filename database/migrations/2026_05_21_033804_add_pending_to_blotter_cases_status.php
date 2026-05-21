<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE blotter_cases MODIFY COLUMN status ENUM(
            'Pending',
            'Active',
            'Under Investigation',
            'Mediated',
            'Settled',
            'Closed',
            'Referred to Higher Authority'
        ) NOT NULL DEFAULT 'Active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE blotter_cases MODIFY COLUMN status ENUM(
            'Active',
            'Under Investigation',
            'Mediated',
            'Settled',
            'Closed',
            'Referred to Higher Authority'
        ) NOT NULL DEFAULT 'Active'");
    }
};
