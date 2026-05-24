<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add released_to and released_by_user_id to the documents table.
     *
     * released_to          — name of the person who physically received the document
     *                        (may differ from the resident when a representative picks up)
     * released_by_user_id  — FK to users: the staff member who handed it over
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('released_to', 255)->nullable()->after('released_at');
            $table->foreignId('released_by_user_id')
                  ->nullable()
                  ->after('released_to')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['released_by_user_id']);
            $table->dropColumn(['released_to', 'released_by_user_id']);
        });
    }
};
