<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Normalized address used to group residents into households automatically
            $table->string('address_key')->nullable()->after('address')->index();
            // 'auto' = grouped by address; 'manual' = staff chose the household (never overwritten)
            $table->enum('household_assignment', ['auto', 'manual'])->default('auto')->after('household_id');
            $table->enum('relationship_to_head', [
                'Head', 'Spouse', 'Child', 'Parent', 'Sibling', 'Grandchild', 'Other Relative', 'Non-relative',
            ])->nullable()->after('household_assignment');
        });

        Schema::table('households', function (Blueprint $table) {
            $table->string('address_key')->nullable()->after('address');
            $table->foreignId('head_resident_id')->nullable()->after('household_head')
                ->constrained('residents')->nullOnDelete();
            // One household per address per purok. NULL keys (not yet normalized) don't collide.
            $table->unique(['purok_id', 'address_key']);
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropUnique(['purok_id', 'address_key']);
            $table->dropConstrainedForeignId('head_resident_id');
            $table->dropColumn('address_key');
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->dropIndex(['address_key']);
            $table->dropColumn(['address_key', 'household_assignment', 'relationship_to_head']);
        });
    }
};
