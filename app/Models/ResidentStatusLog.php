<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use App\Enums\ResidencyStatus;
use Illuminate\Database\Eloquent\Model;

class ResidentStatusLog extends Model
{
    use Archivable;

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
        return $this->belongsTo(Resident::class)->withTrashed();
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by')->withTrashed();
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
