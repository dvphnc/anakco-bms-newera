<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_slug',
        'event_name',
        'event_date',
        'venue',
        'total_attendees',
        'notes',
        'file_path',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeForCommittee($query, string $slug)
    {
        return $query->where('committee_slug', $slug);
    }
}
