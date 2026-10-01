<?php

namespace App\Models;

use App\Enums\ResidencyStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * One entry in the religion list. INC is flagged with is_inc so the
 * "INC / Non-INC" split does not depend on how the name is spelled.
 */
class Religion extends Model
{
    protected $fillable = ['name', 'is_inc', 'is_active'];

    protected function casts(): array
    {
        return ['is_inc' => 'boolean', 'is_active' => 'boolean'];
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    /** Living residents only: the number staff care about. */
    public function livingResidents()
    {
        return $this->residents()->where('residency_status', ResidencyStatus::Alive->value);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
