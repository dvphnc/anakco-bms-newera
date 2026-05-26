<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotter_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blotter_case_id')
                  ->constrained('blotter_cases')
                  ->cascadeOnDelete();
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->string('changed_by', 100)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotter_status_logs');
    }
};
