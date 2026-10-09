<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Model;

/** A Pabahay: a housing block / compound for ministers' families. Its rooms are PabahayUnits. */
class Pabahay extends Model
{
    use Archivable;

    protected $fillable = ['name', 'location', 'purok_id', 'notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function purok()
    {
        return $this->belongsTo(Purok::class)->withTrashed();
    }

    public function units()
    {
        return $this->hasMany(PabahayUnit::class)->ordered();
    }
}
