<?php

namespace App\Models;

use App\Enums\ResidencyStatus;
use App\Services\HouseholdGroupingService;
use App\Support\AddressNormalizer;
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
        'household_assignment',
        'relationship_to_head',
        'residing_since',
        'is_voter',
        'precinct_no',
        'voters_id_no',
        'is_pwd',
        'is_solo_parent',
        'is_4ps',
        'residency_status',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'status_effective_date' => 'date',
            'residing_since' => 'date',
            'is_voter' => 'boolean',
            'is_pwd' => 'boolean',
            'is_solo_parent' => 'boolean',
            'is_4ps' => 'boolean',
        ];
    }

    /** Senior citizen age (RA 9994 — Expanded Senior Citizens Act) */
    public const SENIOR_AGE = 60;

    public const RELATIONSHIPS = [
        'Head', 'Spouse', 'Child', 'Parent', 'Sibling', 'Grandchild', 'Other Relative', 'Non-relative',
    ];

    protected static function booted(): void
    {
        // Task 1.2 — keep the grouping key in step with the typed address
        static::saving(function (Resident $resident) {
            if ($resident->isDirty('address') || $resident->address_key === null) {
                $resident->address_key = AddressNormalizer::key($resident->address);
            }
        });

        // Group into a household by address, and keep household head / size current.
        // (Separate created/updated events on purpose: wasRecentlyCreated stays true for
        // the object's whole lifetime, so a later edit would look like a new registration.)
        static::created(function (Resident $resident) {
            app(HouseholdGroupingService::class)->residentSaved($resident, null, regroup: true);
        });

        static::updated(function (Resident $resident) {
            $grouping = app(HouseholdGroupingService::class);

            // Where the resident lives changed → (re)group by address
            $regroup = $resident->wasChanged(['address_key', 'purok_id', 'residency_status', 'household_assignment'])
                || ($resident->household_id === null && $grouping->shouldAutoGroup($resident));

            // Something that affects the household's head / size / voter flag changed
            $refresh = $resident->wasChanged(['household_id', 'relationship_to_head', 'is_voter', 'birthdate', 'first_name', 'last_name']);

            if ($regroup || $refresh) {
                // getOriginal() still holds the pre-save values inside the updated event
                $grouping->residentSaved($resident, $resident->getOriginal('household_id'), $regroup);
            }
        });

        // A removed resident no longer counts toward their household
        static::deleted(function (Resident $resident) {
            if ($resident->household_id && ($household = Household::find($resident->household_id))) {
                app(HouseholdGroupingService::class)->refresh($household);
            }
        });
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

    // Senior citizen = 60 or older, worked out from the birthdate so it is never out of date
    public function getIsSeniorAttribute(): bool
    {
        return $this->birthdate !== null && $this->age >= self::SENIOR_AGE;
    }

    // Whole years living in the barangay, from residing_since (null when unknown)
    public function getYearsOfResidencyAttribute(): ?int
    {
        return $this->residing_since?->age;
    }

    // "Alive" / "Deceased" / "Moved Out" — display label for residency_status
    public function getResidencyLabelAttribute(): string
    {
        return ResidencyStatus::labelFor($this->residency_status);
    }

    public function getResidencyBadgeAttribute(): string
    {
        return ResidencyStatus::badgeFor($this->residency_status);
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

    public function statusLogs()
    {
        return $this->hasMany(ResidentStatusLog::class)->latest('id');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('residency_status', 'Active');
    }

    // Every resident flagged as a registered voter, whatever their status.
    // For counts and reports, use residentVoters() instead.
    public function scopeVoters($query)
    {
        return $query->where('is_voter', true);
    }

    // Registered voters who currently live in the barangay (Alive — excludes
    // residents who have moved out or died).
    public function scopeResidentVoters($query)
    {
        return $query->where('is_voter', true)->where('residency_status', ResidencyStatus::Alive->value);
    }

    // Still flagged as registered here but have moved out — candidates for the
    // voter-list cleanup report (they usually stay on the COMELEC list until they
    // transfer their registration).
    public function scopeNonResidentVoters($query)
    {
        return $query->where('is_voter', true)->where('residency_status', ResidencyStatus::MovedOut->value);
    }

    // Senior citizens: 60 or older today (calculated from birthdate — there is no stored flag)
    public function scopeSeniors($query)
    {
        return $query->whereDate('birthdate', '<=', now()->subYears(self::SENIOR_AGE)->toDateString());
    }

    public function scopePwd($query)
    {
        return $query->where('is_pwd', true);
    }
}
