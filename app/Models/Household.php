<?php

namespace App\Models;

use App\Enums\ResidencyStatus;
use App\Support\AddressNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_number',
        'purok_id',
        'address',
        'address_key',
        'household_head',      // cached display name of the head — kept in sync by HouseholdGroupingService
        'head_resident_id',
        'family_size',         // number of living members — kept in sync by HouseholdGroupingService
        'is_voter_household',  // any living member is a registered voter — kept in sync
    ];

    protected function casts(): array
    {
        return [
            'is_voter_household' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Keep the grouping key in step with the address, however the household is saved
        static::saving(function (Household $household) {
            if ($household->isDirty('address') || $household->address_key === null) {
                $household->address_key = AddressNormalizer::key($household->address);
            }
        });
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

    // Members still living in the household (excludes deceased / moved out)
    public function livingMembers()
    {
        return $this->hasMany(Resident::class)->where('residency_status', ResidencyStatus::Alive->value);
    }

    public function head()
    {
        return $this->belongsTo(Resident::class, 'head_resident_id');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function getActiveResidentsCountAttribute(): int
    {
        return $this->residents()->where('residency_status', 'Active')->count();
    }

    /**
     * Next household number for this year, e.g. "HH-2026-0042".
     *
     * Based on the highest existing number rather than a count, so deleting a
     * household can never make the next number collide with an existing one.
     */
    public static function nextNumber(): string
    {
        $prefix = 'HH-'.now()->format('Y').'-';
        $last   = static::where('household_number', 'like', $prefix.'%')
            ->orderByRaw('CAST(SUBSTRING(household_number, ?) AS UNSIGNED) DESC', [strlen($prefix) + 1])
            ->value('household_number');
        $next   = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
