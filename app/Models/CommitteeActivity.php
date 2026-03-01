<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommitteeActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_slug',
        'activity_type',
        'title',
        'description',
        'activity_date',
        'location',
        'participants_count',
        'status',
        'logged_by',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function loggedBy()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeForCommittee($query, string $slug)
    {
        return $query->where('committee_slug', $slug);
    }

    public function scopeActivities($query)
    {
        return $query->where('activity_type', 'Activity');
    }

    public function scopeAccomplishments($query)
    {
        return $query->where('activity_type', 'Accomplishment');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }
}