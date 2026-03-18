<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('loggable_type');  // Model class e.g. App\Models\Resident
            $table->unsignedBigInteger('loggable_id'); // Record ID
            $table->string('action');         // created, updated, deleted
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('changes')->nullable(); // JSON of what changed
            $table->timestamps();

            $table->index(['loggable_type', 'loggable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};