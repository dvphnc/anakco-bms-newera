<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Model;

/**
 * Part 3.2: a role is a named set of permissions. The system role (Admin) always has
 * every permission and cannot be edited or archived, so the system can never be locked out.
 */
class Role extends Model
{
    use Archivable;

    public const ADMIN = 'Admin';

    protected $fillable = ['name', 'description', 'is_system'];

    protected $casts = ['is_system' => 'boolean'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->orderBy('sort');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissionKeys(): array
    {
        return $this->is_system ? \App\Support\Permissions::keys() : $this->permissions->pluck('key')->all();
    }

    public static function admin(): self
    {
        return static::where('is_system', true)->firstOrFail();
    }
}
