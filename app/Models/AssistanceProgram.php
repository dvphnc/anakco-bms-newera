<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssistanceProgram extends Model
{
    use SoftDeletes;

    public const TYPES = [
        'relief'     => 'Relief Goods',
        'medicine'   => 'Medicine',
        'financial'  => 'Financial Assistance',
        'livelihood' => 'Livelihood',
        'other'      => 'Other',
    ];

    protected $fillable = [
        'name', 'type', 'claim_scope', 'max_claims', 'starts_on', 'ends_on', 'description', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_on'  => 'date',
            'ends_on'    => 'date',
            'is_active'  => 'boolean',
            'max_claims' => 'integer',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(ResidentTransaction::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getScopeLabelAttribute(): string
    {
        $per = $this->claim_scope === 'household' ? 'household' : 'resident';

        return $this->max_claims === 1 ? "Once per $per" : "{$this->max_claims}× per $per";
    }

    /** Active and today falls within the program period (open-ended if a date is blank). */
    public function isOpen(): bool
    {
        $today = now()->startOfDay();

        return $this->is_active
            && (! $this->starts_on || $this->starts_on->lte($today))
            && (! $this->ends_on || $this->ends_on->gte($today));
    }

    public function scopeOpen($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_on')->orWhere('starts_on', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ends_on')->orWhere('ends_on', '>=', $today));
    }
}
