<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->decimal('fee_paid', 10, 2)->nullable()->after('issued_by');
            $table->string('or_number', 30)->nullable()->after('fee_paid');
            $table->text('remarks')->nullable()->after('or_number');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['fee_paid', 'or_number', 'remarks']);
        });
    }
};
