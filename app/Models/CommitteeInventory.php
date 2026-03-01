<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommitteeInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_slug',
        'item_name',
        'category',
        'quantity',
        'unit',
        'condition',
        'remarks',
        'recorded_by',
    ];

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

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Badge color per condition for the blade views
    public function getConditionBadgeAttribute(): string
    {
        return match($this->condition) {
            'Good'        => 'badge-green',
            'Fair'        => 'badge-yellow',
            'Poor'        => 'badge-red',
            'For Disposal'=> 'badge-gray',
            default       => 'badge-gray',
        };
    }
}