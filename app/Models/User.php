<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Archivable;

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',      // the role's name; stored as role_id (see role())
        'role_id',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function issuedDocuments()
    {
        return $this->hasMany(Document::class, 'issued_by');
    }

    public function filedBlotterCases()
    {
        return $this->hasMany(BlotterCase::class, 'filed_by');
    }

    public function issuedBusinessPermits()
    {
        return $this->hasMany(Business::class, 'issued_by');
    }

    // -------------------------------------------------------
    // Role and permissions (Part 3.2)
    // -------------------------------------------------------

    /** Permission keys for this request, so each check is not a query */
    private ?array $permissionKeys = null;

    protected static function booted(): void
    {
        // Accounts made without a role (sign-up page, factories) get Secretary, as before
        static::creating(function (User $user) {
            $user->role_id ??= Role::where('name', 'Secretary')->value('id');
        });
        static::saved(fn (User $user) => $user->permissionKeys = null);
    }

    public function assignedRole()
    {
        return $this->belongsTo(Role::class, 'role_id')->withTrashed();
    }

    /** $user->role is still the role's name, and setting it by name still works */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->assignedRole?->name,
            set: function (?string $name) {
                if ($name === null) {
                    return ['role_id' => null];
                }
                $id = Role::where('name', $name)->value('id');
                if (! $id) {
                    throw new \InvalidArgumentException("Unknown role [$name].");
                }
                $this->unsetRelation('assignedRole');

                return ['role_id' => $id];
            },
        );
    }

    public function scopeWithRole(Builder $query, string $name): Builder
    {
        return $query->whereHas('assignedRole', fn ($q) => $q->where('name', $name));
    }

    /** Users holding the locked Admin role */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->whereHas('assignedRole', fn ($q) => $q->where('is_system', true));
    }

    public function hasPermission(string $key): bool
    {
        return in_array($key, $this->permissionKeys(), true);
    }

    public function hasAnyPermission(array $keys): bool
    {
        return (bool) array_intersect($keys, $this->permissionKeys());
    }

    public function permissionKeys(): array
    {
        if ($this->permissionKeys === null) {
            $role = $this->assignedRole;
            $this->permissionKeys = $role && ! $role->trashed() ? $role->permissionKeys() : [];
        }

        return $this->permissionKeys;
    }

    /** Holds the locked Admin role (all permissions) */
    public function isAdmin(): bool
    {
        return (bool) $this->assignedRole?->is_system;
    }
}
