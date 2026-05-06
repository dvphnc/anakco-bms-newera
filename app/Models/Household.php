<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_number',
        'purok_id',
        'address',
        'household_head',
        'family_size',
        'is_voter_household',
    ];

    protected function casts(): array
    {
        return [
            'is_voter_household' => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function purok()
    {
        return $this->belongsTo(Purok::class);
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function getActiveResidentsCountAttribute(): int
    {
        return $this->residents()->where('residency_status', 'Active')->count();
    }
}
