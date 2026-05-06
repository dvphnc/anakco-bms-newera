<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puroks', function (Blueprint $table) {
            $table->unsignedBigInteger('leader_id')->nullable()->after('description');
            $table->foreign('leader_id')->references('id')->on('residents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('puroks', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropColumn('leader_id');
        });
    }
};
