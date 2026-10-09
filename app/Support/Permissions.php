<?php

namespace App\Support;

use App\Models\Permission;

/**
 * Part 3.2: every action the system checks, in one list.
 *
 * Routes, views and controllers check these keys (never a role name). The Admin page
 * at Roles & Permissions decides which role gets which key, so access can change without
 * touching the code. To add an action: add it here, check it where it is used, and it shows
 * up in the matrix on the next page load (sync() adds the database row).
 */
class Permissions
{
    /** group => [key => label] */
    public const GROUPS = [
        'Residents' => [
            'residents.view'    => 'View residents',
            'residents.create'  => 'Add residents',
            'residents.edit'    => 'Edit residents',
            'residents.status'  => 'Change life status (Alive, Deceased, Moved Out)',
            'residents.revive'  => 'Change the status of a deceased resident',
            'residents.archive' => 'Archive residents',
        ],
        'Religion and Pabahay' => [
            'religion.view'   => 'See religion, Family of Ministers and Pabahay details',
            'religion.manage' => 'Manage the religion list and Pabahay units',
        ],
        'Households and Puroks' => [
            'households.view'    => 'View households',
            'households.create'  => 'Add households',
            'households.edit'    => 'Edit households and set the head',
            'households.archive' => 'Archive households',
            'puroks.view'        => 'View puroks',
            'puroks.edit'        => 'Edit puroks',
        ],
        'Assistance and Claims' => [
            'programs.view'       => 'View assistance programs',
            'programs.manage'     => 'Add and edit assistance programs',
            'programs.archive'    => 'Archive assistance programs',
            'transactions.view'   => 'View a resident\'s transaction history',
            'transactions.record' => 'Record a claim or transaction',
            'transactions.void'   => 'Void a transaction',
        ],
        'Documents' => [
            'documents.view'    => 'View issued documents',
            'documents.create'  => 'Issue documents',
            'documents.edit'    => 'Edit documents and change their status',
            'documents.archive' => 'Archive documents',
        ],
        'Blotter' => [
            'blotter.view'    => 'View blotter cases',
            'blotter.create'  => 'File blotter cases',
            'blotter.edit'    => 'Edit blotter cases and change their status',
            'blotter.archive' => 'Archive blotter cases',
        ],
        'Business Permits' => [
            'businesses.view'    => 'View business permits',
            'businesses.create'  => 'Add business permits',
            'businesses.edit'    => 'Edit business permits and change their status',
            'businesses.archive' => 'Archive business permits',
        ],
        'Officials' => [
            'officials.view'    => 'View officials and staff',
            'officials.create'  => 'Add officials and staff',
            'officials.edit'    => 'Edit officials and staff',
            'officials.archive' => 'Archive officials and staff',
        ],
        'Portal Requests' => [
            'appointments.view'    => 'View requests from the Resident Portal',
            'appointments.process' => 'Process portal requests (status, convert, issue)',
            'appointments.archive' => 'Archive portal requests',
        ],
        'Committees' => [
            'committees.view'    => 'Open the committee pages',
            'committees.manage'  => 'Add and edit committee records',
            'committees.archive' => 'Archive committee records',
        ],
        'Reports' => [
            'reports.view'   => 'View reports and analytics',
            'reports.export' => 'Export to Excel and PDF',
            'activity-log.view' => 'View the activity log',
        ],
        'System' => [
            'users.manage'       => 'Manage user accounts',
            'roles.manage'       => 'Manage roles and permissions',
            'backup.manage'      => 'Back up and restore the database',
            'recycle-bin.manage' => 'Open the Recycle Bin and restore records',
        ],
    ];

    /** Shown with a warning in the matrix: they expose private data or control access itself */
    public const SENSITIVE = [
        'religion.view', 'religion.manage', 'users.manage', 'roles.manage', 'backup.manage', 'residents.revive',
    ];

    /** What each built-in role had before roles became editable, so nobody gains or loses access */
    public const DEFAULTS = [
        'Committee' => ['committees.view', 'committees.manage', 'committees.archive'],
        // Secretary: everything except these
        'Secretary' => ['*', '-religion.view', '-religion.manage', '-residents.revive',
            '-users.manage', '-roles.manage', '-backup.manage', '-recycle-bin.manage'],
    ];

    public static function keys(): array
    {
        return array_merge(...array_map('array_keys', array_values(self::GROUPS)));
    }

    public static function exists(string $key): bool
    {
        return in_array($key, self::keys(), true);
    }

    public static function label(string $key): string
    {
        foreach (self::GROUPS as $perms) {
            if (isset($perms[$key])) {
                return $perms[$key];
            }
        }

        return $key;
    }

    /** Keys a built-in role starts with */
    public static function defaultsFor(string $role): array
    {
        $rules = self::DEFAULTS[$role] ?? [];
        $keys  = in_array('*', $rules, true) ? self::keys() : array_filter($rules, fn ($r) => ! str_starts_with($r, '-'));
        $minus = array_map(fn ($r) => substr($r, 1), array_filter($rules, fn ($r) => str_starts_with($r, '-')));

        return array_values(array_diff($keys, $minus));
    }

    /** Make the permissions table match the list above (adds new ones, updates labels, archives removed ones). */
    public static function sync(): void
    {
        $sort = 0;
        $keys = [];
        foreach (self::GROUPS as $group => $perms) {
            foreach ($perms as $key => $label) {
                $keys[] = $key;
                $permission = Permission::withTrashed()->firstOrNew(['key' => $key]);
                $permission->fill(['label' => $label, 'group' => $group, 'sort' => $sort++]);
                $permission->save();
                if ($permission->trashed()) {
                    $permission->restore();
                }
            }
        }

        Permission::whereNotIn('key', $keys)->get()->each->delete();
    }

    public static function inSync(): bool
    {
        return Permission::count() === count(self::keys());
    }
}
