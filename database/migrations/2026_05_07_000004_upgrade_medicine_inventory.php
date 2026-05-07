<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add pharmacy-grade columns to existing medicine inventory table
        Schema::table('committee_medicine_inventory', function (Blueprint $table) {
            $table->string('brand_name')->nullable()->after('medicine_name');
            $table->string('category')->nullable()->after('generic_name');
            $table->string('dosage_form')->nullable()->after('category'); // e.g. 500mg Tablet
            $table->string('barcode')->nullable()->after('batch_number');
        });

        // Stock transaction log for full audit trail
        Schema::create('medicine_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')
                  ->constrained('committee_medicine_inventory')
                  ->cascadeOnDelete();
            $table->enum('adjustment_type', ['in', 'out', 'disposed']);
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reason')->nullable();
            $table->string('performed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_stock_logs');

        Schema::table('committee_medicine_inventory', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'category', 'dosage_form', 'barcode']);
        });
    }
};
