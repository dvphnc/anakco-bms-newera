<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One thing a resident received (document, medicine, relief goods, cash aid…).
 * Never deleted: mistakes are voided with a reason.
 */
class ResidentTransaction extends Model
{
    public const TYPES = [
        'document'   => 'Document',
        'medicine'   => 'Medicine',
        'relief'     => 'Relief Goods',
        'financial'  => 'Financial Assistance',
        'livelihood' => 'Livelihood',
        'other'      => 'Other',
    ];

    public const TYPE_BADGES = [
        'document'   => 'badge-navy',
        'medicine'   => 'badge-green',
        'relief'     => 'badge-gold',
        'financial'  => 'badge-blue',
        'livelihood' => 'badge-orange',
        'other'      => 'badge-gray',
    ];

    protected $fillable = [
        'reference_no', 'resident_id', 'household_id', 'assistance_program_id', 'type',
        'source_type', 'source_id', 'description', 'quantity', 'unit', 'amount',
        'transacted_at', 'processed_by', 'claim_lock',
    ];

    protected function casts(): array
    {
        return [
            'transacted_at' => 'datetime',
            'voided_at'     => 'datetime',
            'quantity'      => 'decimal:2',
            'amount'        => 'decimal:2',
        ];
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function program()
    {
        return $this->belongsTo(AssistanceProgram::class, 'assistance_program_id')->withTrashed();
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function voidedBy()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function scopeValid($query)
    {
        return $query->whereNull('voided_at');
    }

    public function getIsVoidedAttribute(): bool
    {
        return $this->voided_at !== null;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getTypeBadgeAttribute(): string
    {
        return self::TYPE_BADGES[$this->type] ?? 'badge-gray';
    }
}
