<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('filed_by');
            $table->string('file_type')->nullable()->after('file_path');
            $table->string('file_original_name')->nullable()->after('file_type');
        });
    }

    public function down(): void
    {
        Schema::table('blotter_cases', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_type', 'file_original_name']);
        });
    }
};
