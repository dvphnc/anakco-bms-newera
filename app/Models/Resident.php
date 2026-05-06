<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'birthdate',
        'gender',
        'civil_status',
        'birthplace',
        'nationality',
        'religion',
        'occupation',
        'contact_number',
        'email_address',
        'address',
        'purok_id',
        'household_id',
        'is_voter',
        'is_pwd',
        'is_senior',
        'is_solo_parent',
        'is_4ps',
        'residency_status',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'is_voter' => 'boolean',
            'is_pwd' => 'boolean',
            'is_senior' => 'boolean',
            'is_solo_parent' => 'boolean',
            'is_4ps' => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    // Full name: "Juan D. Santos"
    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name
            ? ' '.substr($this->middle_name, 0, 1).'.'
            : '';

        $suffix = $this->suffix ? ' '.$this->suffix : '';

        return $this->first_name.$middle.' '.$this->last_name.$suffix;
    }

    // Full name for lists: "SANTOS, Juan D."
    public function getFullNameFormalAttribute(): string
    {
        $middle = $this->middle_name
            ? ' '.substr($this->middle_name, 0, 1).'.'
            : '';

        return strtoupper($this->last_name).', '.$this->first_name.$middle;
    }

    // Age computed from birthdate
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birthdate)->age;
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function purok()
    {
        return $this->belongsTo(Purok::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function blotterCases()
    {
        return $this->hasMany(BlotterCase::class, 'complainant_resident_id');
    }

    public function businessPermits()
    {
        return $this->hasMany(Business::class, 'owner_resident_id');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('residency_status', 'Active');
    }

    public function scopeVoters($query)
    {
        return $query->where('is_voter', true);
    }

    public function scopeSeniors($query)
    {
        return $query->where('is_senior', true);
    }

    public function scopePwd($query)
    {
        return $query->where('is_pwd', true);
    }
}
