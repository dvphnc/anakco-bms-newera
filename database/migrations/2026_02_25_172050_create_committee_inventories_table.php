<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_inventories', function (Blueprint $table) {
            $table->id();

            $table->enum('committee_slug', [
                'peace-order',
                'health',
                'education',
                'infrastructure',
                'environment',
                'livelihood',
                'transport',
                'bdrrm',
            ]);

            $table->string('item_name');
            $table->string('category')->nullable();         // e.g. "Equipment", "Supplies"
            $table->integer('quantity')->default(0);
            $table->string('unit')->nullable();             // pcs, kg, boxes, etc.
            $table->enum('condition', [
                'Good', 'Fair', 'Poor', 'For Disposal',
            ])->default('Good');
            $table->text('remarks')->nullable();

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_inventories');
    }
};
