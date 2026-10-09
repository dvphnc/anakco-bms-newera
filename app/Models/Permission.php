<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Model;

/**
 * Part 3.2: one action the system checks (for example "residents.archive").
 * The list itself lives in App\Support\Permissions and is copied here by Permissions::sync().
 */
class Permission extends Model
{
    use Archivable;

    protected $fillable = ['key', 'label', 'group', 'sort'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
