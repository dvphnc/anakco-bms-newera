<?php

namespace App\Models;

use App\Enums\ResidencyStatus;
use Illuminate\Database\Eloquent\Model;

class ResidentStatusLog extends Model
{
    protected $fillable = [
        'resident_id',
        'from_status',
        'to_status',
        'effective_date',
        'moved_to',
        'remarks',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
        ];
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getFromLabelAttribute(): string
    {
        return ResidencyStatus::labelFor($this->from_status);
    }

    public function getToLabelAttribute(): string
    {
        return ResidencyStatus::labelFor($this->to_status);
    }
}
