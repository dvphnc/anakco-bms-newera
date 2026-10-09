<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Support\Permissions;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Part 3.2: the Admin creates roles and ticks which permissions each one has.
 * Nothing here needs a code change: routes and pages check the permission keys.
 */
class RoleController extends Controller
{
    use LogsActivity;

    public function index()
    {
        if (! Permissions::inSync()) {
            Permissions::sync();
        }

        $roles = Role::with('permissions')->withCount('users')
            ->orderByDesc('is_system')->orderBy('id')->get();

        $groups = Permission::orderBy('sort')->get()->groupBy('group');

        return view('roles.index', [
            'roles'     => $roles,
            'groups'    => $groups,
            'sensitive' => Permissions::SENSITIVE,
            'myRoleId'  => auth()->user()->role_id,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:50', $this->uniqueName()],
            'description' => ['nullable', 'string', 'max:255'],
            'copy_from'   => ['nullable', Rule::exists('roles', 'id')->whereNull('deleted_at')],
        ]);

        $role = DB::transaction(function () use ($data) {
            $role = Role::create(['name' => trim($data['name']), 'description' => $data['description'] ?? null]);
            if (! empty($data['copy_from'])) {
                $keys = $this->grantable(Role::find($data['copy_from'])->permissionKeys());
                $role->permissions()->sync(Permission::whereIn('key', $keys)->pluck('id'));
            }

            return $role;
        });
        $this->logActivity('created', $role);

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$role->name}\" created. Tick its permissions below, then save.");
    }

    public function update(Request $request, Role $role)
    {
        $this->abortIfLocked($role);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:50', $this->uniqueName($role)],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $old = $role->getOriginal();
        $role->update(['name' => trim($data['name']), 'description' => $data['description'] ?? null]);
        $this->logActivity('updated', $role, $old, $role->fresh()->toArray());

        return redirect()->route('roles.index')->with('success', "Role \"{$role->name}\" updated.");
    }

    /** Saves the whole matrix: perms[role id][] = permission key */
    public function permissions(Request $request)
    {
        $request->validate([
            'roles' => ['required', 'array'], 'roles.*' => ['integer'],
            'perms' => ['nullable', 'array'], 'perms.*' => ['array'], 'perms.*.*' => ['string'],
        ]);

        $posted = $request->input('perms', []);
        $onPage = $request->input('roles');
        $ids    = Permission::pluck('id', 'key');
        $saved  = [];

        DB::transaction(function () use ($posted, $onPage, $ids, &$saved) {
            // Only roles that were on the page (a role added meanwhile is left alone).
            // A listed role with nothing ticked means "no permissions".
            foreach (Role::where('is_system', false)->whereIn('id', $onPage)->with('permissions')->get() as $role) {
                if ($role->id === auth()->user()->role_id && ! auth()->user()->isAdmin()) {
                    continue;   // nobody changes their own access
                }

                $before = $role->permissions->pluck('key')->all();
                $wanted = array_values(array_intersect(array_unique($posted[$role->id] ?? []), $ids->keys()->all()));

                // You can only give what you have; what you do not have stays as it was
                $mine   = auth()->user()->permissionKeys();
                $wanted = array_values(array_unique(array_merge(
                    array_intersect($wanted, $mine),
                    array_diff($before, $mine)
                )));

                $added   = array_diff($wanted, $before);
                $removed = array_diff($before, $wanted);
                if (! $added && ! $removed) {
                    continue;
                }

                $role->permissions()->sync($ids->only($wanted)->values());
                $this->logPermissionChange($role, $added, $removed);
                $saved[] = $role->name;
            }
        });

        return redirect()->route('roles.index')->with('success', $saved
            ? 'Permissions saved for '.implode(', ', $saved).'.'
            : 'No changes to save.');
    }

    public function destroy(Role $role)
    {
        $this->abortIfLocked($role);

        if ($count = $role->users()->count()) {
            return back()->with('error', "\"{$role->name}\" still has {$count} ".str('user')->plural($count).'. Move them to another role first.');
        }
        if ($role->id === auth()->user()->role_id) {
            return back()->with('error', 'You cannot archive your own role.');
        }

        $this->logActivity('deleted', $role);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$role->name}\" archived. You can restore it from the Recycle Bin.");
    }

    private function abortIfLocked(Role $role): void
    {
        if ($role->is_system) {
            throw ValidationException::withMessages(['role' => 'The Admin role always has every permission and cannot be changed.']);
        }
    }

    private function grantable(array $keys): array
    {
        return array_values(array_intersect($keys, auth()->user()->permissionKeys()));
    }

    private function uniqueName(?Role $role = null): \Closure
    {
        return function ($attribute, $value, $fail) use ($role) {
            $match = Role::withTrashed()->where('name', trim($value))->when($role, fn ($q) => $q->whereKeyNot($role->id))->first();
            if ($match) {
                $fail($match->trashed()
                    ? 'A role with this name is archived. Restore it from the Recycle Bin instead.'
                    : 'A role with this name already exists.');
            }
        };
    }

    /** One activity log entry per role, listing each permission that was turned on or off */
    private function logPermissionChange(Role $role, array $added, array $removed): void
    {
        $changes = [];
        foreach ($added as $key) {
            $changes[Permissions::label($key)] = ['old' => 'No', 'new' => 'Yes'];
        }
        foreach ($removed as $key) {
            $changes[Permissions::label($key)] = ['old' => 'Yes', 'new' => 'No'];
        }
        ActivityLog::log('updated', $role, $changes);
    }
}
