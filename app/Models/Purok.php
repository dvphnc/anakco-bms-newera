<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purok extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function leader()
    {
        return $this->belongsTo(Resident::class, 'leader_id');
    }

    public function households()
    {
        return $this->hasMany(Household::class);
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function getTotalResidentsAttribute(): int
    {
        return $this->residents()->where('residency_status', 'Active')->count();
    }

    public function getTotalHouseholdsAttribute(): int
    {
        return $this->households()->count();
    }
}
