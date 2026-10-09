<?php

use App\Support\Permissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Part 3.2: roles and permissions live in the database instead of the code.
 *
 * The three roles that existed before (Admin, Secretary, Committee) are created with exactly
 * the access they had, and every user keeps their role. users.role (a fixed list) becomes
 * users.role_id, so the Admin can add new roles from the app.
 */
return new class extends Migration
{
    private const BUILT_IN = [
        'Admin'     => 'Full access to everything. Always has every permission and cannot be changed.',
        'Secretary' => 'Day-to-day records: residents, documents, blotter, business permits, officials and reports.',
        'Committee' => 'Committee pages only.',
    ];

    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false);   // the Admin role: all permissions, locked
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key', 60)->unique();
            $table->string('label');
            $table->string('group', 60);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        $now = now();
        foreach (self::BUILT_IN as $name => $description) {
            DB::table('roles')->insert([
                'name' => $name, 'description' => $description, 'is_system' => $name === 'Admin',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        Permissions::sync();

        $permissionIds = DB::table('permissions')->pluck('id', 'key');
        foreach (['Secretary', 'Committee'] as $role) {
            $roleId = DB::table('roles')->where('name', $role)->value('id');
            DB::table('permission_role')->insert(array_map(
                fn ($key) => ['role_id' => $roleId, 'permission_id' => $permissionIds[$key]],
                Permissions::defaultsFor($role)
            ));
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')->constrained('roles');
        });
        foreach (DB::table('roles')->pluck('id', 'name') as $name => $id) {
            DB::table('users')->where('role', $name)->update(['role_id' => $id]);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Admin', 'Secretary', 'Committee'])->default('Secretary')->after('email');
        });
        // Roles added from the app did not exist before, so their users fall back to the lowest access
        DB::table('users')->update(['role' => 'Committee']);
        foreach (['Admin', 'Secretary'] as $name) {
            $id = DB::table('roles')->where('name', $name)->value('id');
            DB::table('users')->where('role_id', $id)->update(['role' => $name]);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });

        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
