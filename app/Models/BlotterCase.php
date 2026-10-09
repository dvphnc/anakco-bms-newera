<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlotterCase extends Model
{
    use Archivable;

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
        'respondent_resident_id',
        'email',
        'respondent_name',
        'respondent_address',
        'respondent_contact',
        'responding_officer',
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
        return $this->belongsTo(Resident::class, 'complainant_resident_id')->withTrashed();
    }

    public function respondentResident()
    {
        return $this->belongsTo(Resident::class, 'respondent_resident_id')->withTrashed();
    }

    public function filedBy()
    {
        return $this->belongsTo(User::class, 'filed_by')->withTrashed();
    }

    public function statusLogs()
    {
        return $this->hasMany(BlotterStatusLog::class, 'blotter_case_id')->orderBy('created_at');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Generate next case number e.g. CASE-2025-0001
    public static function generateCaseNumber(): string
    {
        $year = date('Y');
        $prefix = 'CASE-'.$year.'-';
        // Highest number so far (archived records included), so a number is never reused
        $max = self::withTrashed()->where('case_number', 'like', $prefix.'%')
            ->selectRaw('MAX(CAST(SUBSTRING(case_number, ?) AS UNSIGNED)) as max_seq', [strlen($prefix) + 1])
            ->value('max_seq');

        return $prefix.str_pad(($max ?? 0) + 1, 4, '0', STR_PAD_LEFT);
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function isSettled(): bool
    {
        return $this->status === 'Settled';
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
            'Referred to Higher Authority' => 'badge-orange',
            default                        => 'badge-gray',
        };
    }
}
