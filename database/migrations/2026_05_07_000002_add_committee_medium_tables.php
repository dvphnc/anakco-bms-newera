<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Generic Partnership Records — all 8 committees
        Schema::create('committee_partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('committee_slug');
            $table->string('partner_name');
            $table->enum('partner_type', ['Government', 'NGO', 'Private', 'Community', 'Other'])->default('Other');
            $table->date('mou_date')->nullable();
            $table->date('validity_date')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // MOU document
            $table->timestamps();
        });

        // Health — Medicine Inventory
        Schema::create('committee_medicine_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name');
            $table->string('generic_name')->nullable();
            $table->string('unit')->default('tablets'); // tablets, vials, sachets, bottles
            $table->integer('current_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->date('expiry_date')->nullable();
            $table->string('supplier')->nullable();
            $table->string('batch_number')->nullable();
            $table->timestamps();
        });

        // BDRRM — Relief Supplies
        Schema::create('committee_relief_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->enum('category', ['Food', 'Non-food', 'Medicine', 'PPE', 'Equipment', 'Other'])->default('Food');
            $table->integer('quantity')->default(0);
            $table->string('unit')->nullable();  // pcs, packs, boxes, sacks
            $table->string('source')->nullable(); // donated by / purchased from
            $table->date('date_received')->nullable();
            $table->enum('status', ['Available', 'Distributed', 'Depleted'])->default('Available');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_relief_supplies');
        Schema::dropIfExists('committee_medicine_inventory');
        Schema::dropIfExists('committee_partnerships');
    }
};
