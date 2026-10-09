<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Part 3.2: roles and permissions live in the database and the Admin changes them from
 * the Roles & Permissions page. Nothing about access is hard-coded to a role name.
 */
class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $secretary;
    private User $committee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin     = User::factory()->create(['role' => 'Admin']);
        $this->secretary = User::factory()->create(['role' => 'Secretary']);
        $this->committee = User::factory()->create(['role' => 'Committee']);
    }

    private function role(string $name): Role
    {
        return Role::where('name', $name)->sole();
    }

    /** Post the matrix the way the page does: every editable role listed, with its ticked keys */
    private function saveMatrix(User $as, array $changes)
    {
        $roles = Role::where('is_system', false)->with('permissions')->get();
        $perms = $roles->mapWithKeys(fn ($r) => [$r->id => $r->permissions->pluck('key')->all()])->all();
        foreach ($changes as $roleName => $keys) {
            $perms[$this->role($roleName)->id] = $keys;
        }

        return $this->actingAs($as)->put(route('roles.permissions'), ['roles' => $roles->pluck('id')->all(), 'perms' => $perms]);
    }

    private function keysOf(string $roleName): array
    {
        return $this->role($roleName)->fresh()->permissions->pluck('key')->sort()->values()->all();
    }

    public function test_the_built_in_roles_keep_the_access_they_had(): void
    {
        $this->assertTrue($this->role('Admin')->is_system);
        $this->assertEqualsCanonicalizing(Permissions::keys(), $this->admin->permissionKeys());
        $this->assertEqualsCanonicalizing(['committees.view', 'committees.manage', 'committees.archive'], $this->keysOf('Committee'));

        $secretaryLacks = array_values(array_diff(Permissions::keys(), $this->keysOf('Secretary')));
        $this->assertEqualsCanonicalizing(
            ['religion.view', 'religion.manage', 'residents.revive', 'users.manage', 'roles.manage', 'backup.manage', 'recycle-bin.manage'],
            $secretaryLacks
        );
    }

    public function test_pages_follow_the_permissions_of_each_role(): void
    {
        $this->actingAs($this->secretary)->get(route('residents.index'))->assertOk();
        $this->actingAs($this->secretary)->get(route('reports.index'))->assertOk();
        foreach (['users.index', 'roles.index', 'backup.index', 'recycle-bin.index'] as $page) {
            $this->actingAs($this->secretary)->get(route($page))->assertRedirect(route('dashboard'));
        }

        $this->actingAs($this->committee)->get(route('committees.show', 'health'))->assertOk();
        $this->actingAs($this->committee)->get(route('residents.index'))->assertRedirect(route('dashboard'));
        $this->actingAs($this->committee)->getJson(route('residents.index'))->assertForbidden();

        $this->actingAs($this->admin)->get(route('roles.index'))->assertOk();
    }

    public function test_every_permission_a_route_checks_is_in_the_list(): void
    {
        $known = Permissions::keys();
        foreach (Route::getRoutes() as $route) {
            foreach ($route->gatherMiddleware() as $mw) {
                if (is_string($mw) && str_starts_with($mw, 'permission:')) {
                    foreach (explode(',', substr($mw, 11)) as $key) {
                        $this->assertContains($key, $known, "{$route->uri()} checks unknown permission $key");
                    }
                }
            }
        }
        $this->assertSame(count($known), Permission::count());
        $this->assertTrue(Permissions::inSync());
    }

    public function test_unticking_a_permission_takes_effect_without_any_code_change(): void
    {
        $keys = array_diff($this->keysOf('Secretary'), ['residents.view']);
        $this->saveMatrix($this->admin, ['Secretary' => $keys])->assertRedirect(route('roles.index'));

        $this->assertNotContains('residents.view', $this->keysOf('Secretary'));
        $this->actingAs($this->secretary->fresh())->get(route('residents.index'))->assertRedirect(route('dashboard'));

        $this->saveMatrix($this->admin, ['Secretary' => array_merge($keys, ['residents.view'])]);
        $this->actingAs($this->secretary->fresh())->get(route('residents.index'))->assertOk();
    }

    public function test_permission_changes_are_written_to_the_activity_log(): void
    {
        $this->saveMatrix($this->admin, ['Committee' => ['committees.view', 'residents.view']]);

        $log = ActivityLog::where('loggable_type', Role::class)->where('loggable_id', $this->role('Committee')->id)->latest('id')->first();
        $this->assertSame('updated', $log->action);
        $this->assertSame(['old' => 'No', 'new' => 'Yes'], $log->changes['View residents']);
        $this->assertSame(['old' => 'Yes', 'new' => 'No'], $log->changes['Add and edit committee records']);
    }

    public function test_the_admin_can_create_a_role_and_give_it_access(): void
    {
        $this->actingAs($this->admin)->post(route('roles.store'), [
            'name' => 'Health Worker', 'description' => 'Barangay health workers', 'copy_from' => $this->role('Committee')->id,
        ])->assertRedirect(route('roles.index'));

        $this->assertEqualsCanonicalizing(['committees.view', 'committees.manage', 'committees.archive'], $this->keysOf('Health Worker'));

        $worker = User::factory()->create(['role' => 'Health Worker']);
        $this->actingAs($worker)->get(route('residents.index'))->assertRedirect(route('dashboard'));

        $this->saveMatrix($this->admin, ['Health Worker' => ['committees.view', 'residents.view']]);
        $this->actingAs($worker->fresh())->get(route('residents.index'))->assertOk();
        $this->actingAs($worker->fresh())->get(route('documents.index'))->assertRedirect(route('dashboard'));

        // and it can be picked when adding a user
        $this->actingAs($this->admin)->get(route('users.create'))->assertSee('Health Worker');
    }

    public function test_role_names_must_be_unique_including_archived_ones(): void
    {
        $this->actingAs($this->admin)->post(route('roles.store'), ['name' => 'Secretary'])->assertSessionHasErrors('name');

        $role = Role::create(['name' => 'Encoder']);
        $role->delete();
        $this->actingAs($this->admin)->post(route('roles.store'), ['name' => 'Encoder'])
            ->assertSessionHasErrors(['name' => 'A role with this name is archived. Restore it from the Recycle Bin instead.']);
    }

    public function test_the_admin_role_can_never_be_changed(): void
    {
        $admin = $this->role('Admin');

        $this->actingAs($this->admin)->put(route('roles.update', $admin), ['name' => 'Boss'])->assertSessionHasErrors('role');
        $this->actingAs($this->admin)->delete(route('roles.destroy', $admin))->assertSessionHasErrors('role');

        // Posting the Admin role in the matrix does nothing
        $this->actingAs($this->admin)->put(route('roles.permissions'), ['roles' => [$admin->id], 'perms' => [$admin->id => []]]);
        $this->assertSame('Admin', $admin->fresh()->name);
        $this->assertNotSoftDeleted($admin);
        $this->assertEqualsCanonicalizing(Permissions::keys(), $this->admin->fresh()->permissionKeys());
    }

    public function test_a_role_with_users_cannot_be_archived_but_an_empty_one_can_and_comes_back(): void
    {
        $this->actingAs($this->admin)->delete(route('roles.destroy', $this->role('Committee')))->assertSessionHas('error');
        $this->assertNotSoftDeleted($this->role('Committee'));

        $empty = Role::create(['name' => 'Encoder']);
        $this->actingAs($this->admin)->delete(route('roles.destroy', $empty))->assertSessionHas('success');
        $this->assertSoftDeleted($empty);

        $this->actingAs($this->admin)->patch(route('recycle-bin.restore', ['role', $empty->id]));
        $this->assertNotSoftDeleted($empty);
    }

    public function test_a_non_admin_who_manages_roles_cannot_raise_access(): void
    {
        // The Admin lets Secretaries manage roles
        $this->saveMatrix($this->admin, ['Secretary' => array_merge($this->keysOf('Secretary'), ['roles.manage'])]);
        $secretary = $this->secretary->fresh();

        // They cannot give what they do not have (users.manage) ...
        $this->saveMatrix($secretary, ['Committee' => ['committees.view', 'users.manage', 'residents.view']]);
        $this->assertEqualsCanonicalizing(['committees.view', 'residents.view'], $this->keysOf('Committee'));

        // ... and cannot change their own role
        $this->saveMatrix($secretary, ['Secretary' => ['residents.view', 'roles.manage']]);
        $this->assertContains('documents.view', $this->keysOf('Secretary'));
    }

    public function test_only_an_admin_can_hand_out_the_admin_role_or_touch_admin_accounts(): void
    {
        $this->saveMatrix($this->admin, ['Secretary' => array_merge($this->keysOf('Secretary'), ['users.manage'])]);
        $secretary = $this->secretary->fresh();

        $this->actingAs($secretary)->post(route('users.store'), [
            'name' => 'New Person', 'email' => 'new@bms.test', 'role' => 'Admin',
            'password' => 'Secret@123', 'password_confirmation' => 'Secret@123',
        ])->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'new@bms.test']);

        $this->actingAs($secretary)->put(route('users.update', $this->admin), [
            'name' => 'Renamed', 'email' => $this->admin->email, 'role' => 'Secretary',
        ])->assertForbidden();
        $this->actingAs($secretary)->delete(route('users.destroy', $this->admin))->assertForbidden();
        $this->assertSame('Admin', $this->admin->fresh()->role);

        // They can still manage non-Admin accounts
        $this->actingAs($secretary)->put(route('users.update', $this->committee), [
            'name' => 'Committee Member', 'email' => $this->committee->email, 'role' => 'Committee',
        ])->assertRedirect(route('users.index'));
    }

    public function test_nobody_can_change_their_own_role(): void
    {
        $this->actingAs($this->admin)->put(route('users.update', $this->admin), [
            'name' => $this->admin->name, 'email' => $this->admin->email, 'role' => 'Committee',
        ]);

        $this->assertSame('Admin', $this->admin->fresh()->role);
    }

    public function test_the_sidebar_only_shows_what_the_role_can_open(): void
    {
        $this->actingAs($this->committee)->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('committees.show', 'health'))
            ->assertDontSee(route('residents.index'), false)
            ->assertDontSee(route('roles.index'), false);

        $this->actingAs($this->admin)->get(route('dashboard'))
            ->assertSee(route('roles.index'), false)
            ->assertSee('Roles & Permissions', false);
    }

    public function test_the_matrix_page_lists_every_permission_and_locks_the_admin_column(): void
    {
        $response = $this->actingAs($this->admin)->get(route('roles.index'))->assertOk();

        foreach (Permissions::keys() as $key) {
            $response->assertSee($key);
        }
        $response->assertSee('The Admin role always has every permission');
        $response->assertSee('name="perms['.$this->role('Secretary')->id.'][]"', false);
        $response->assertDontSee('name="perms['.$this->role('Admin')->id.'][]"', false);
    }

    public function test_a_role_is_stored_by_id_and_unknown_role_names_are_refused(): void
    {
        $this->assertSame($this->role('Secretary')->id, $this->secretary->role_id);

        $this->expectException(\InvalidArgumentException::class);
        User::factory()->create(['role' => 'Mayor']);
    }

    public function test_new_accounts_without_a_role_default_to_secretary_as_before(): void
    {
        $this->assertSame('Secretary', User::factory()->create()->role);
    }

    public function test_religion_data_follows_its_permission(): void
    {
        $this->actingAs($this->secretary)->get(route('religions.index'))->assertForbidden();

        $this->saveMatrix($this->admin, ['Secretary' => array_merge($this->keysOf('Secretary'), ['religion.manage'])]);
        $this->actingAs($this->secretary->fresh())->get(route('religions.index'))->assertOk();
    }
}
