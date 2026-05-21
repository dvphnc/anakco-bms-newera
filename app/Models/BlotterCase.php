<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlotterCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'source',
        'incident_type',
        'incident_date',
        'incident_location',
        'incident_details',
        'complainant_name',
        'complainant_address',
        'complainant_contact',
        'complainant_resident_id',
        'email',
        'respondent_name',
        'respondent_address',
        'respondent_contact',
        'status',
        'resolution_notes',
        'settled_at',
        'filed_by',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'settled_at' => 'date',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function complainantResident()
    {
        return $this->belongsTo(Resident::class, 'complainant_resident_id');
    }

    public function filedBy()
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Generate next case number e.g. CASE-2025-0001
    public static function generateCaseNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;

        return 'CASE-'.$year.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function isSettled(): bool
    {
        return in_array($this->status, ['Settled', 'Closed']);
    }

    // Badge color per status for the blade views
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Pending'                      => 'badge-yellow',
            'Active'                       => 'badge-red',
            'Under Investigation'          => 'badge-yellow',
            'Mediated'                     => 'badge-blue',
            'Settled'                      => 'badge-green',
            'Closed'                       => 'badge-gray',
            'Referred to Higher Authority' => 'badge-orange',
            default                        => 'badge-gray',
        };
    }
}
