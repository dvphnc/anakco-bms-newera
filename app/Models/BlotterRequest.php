<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterRequest extends Model
{
    protected $fillable = [
        'request_number', 'complainant_name', 'contact_number', 'email',
        'address', 'incident_type', 'incident_date', 'incident_location',
        'incident_description', 'respondent_name', 'status', 'notes', 'processed_by',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public static array $statuses = [
        'Pending', 'Under Review', 'For Mediation', 'Resolved', 'Dismissed', 'Cancelled',
    ];

    public static array $incidentTypes = [
        'Physical Assault', 'Verbal Abuse / Threats', 'Property Damage',
        'Noise Complaint', 'Domestic Dispute', 'Theft', 'Trespassing',
        'Harassment', 'Other',
    ];

    public static function generateNumber(): string
    {
        do {
            $num = 'BRQ-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        } while (static::where('request_number', $num)->exists());

        return $num;
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'Pending'       => 'var(--gold)',
            'Under Review'  => '#3a5fa0',
            'For Mediation' => '#a05828',
            'Resolved'      => '#2e6b47',
            'Dismissed'     => '#6b7280',
            'Cancelled'     => '#8b2e2e',
            default         => '#6b7280',
        };
    }
}
