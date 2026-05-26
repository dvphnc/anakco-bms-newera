<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'source',
        'business_name',
        'business_type',
        'business_address',
        'owner_name',
        'owner_contact',
        'owner_resident_id',
        'email',
        'preferred_date',
        'permit_date',
        'expiry_date',
        'status',
        'issued_by',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'permit_date'    => 'date',
            'expiry_date'    => 'date',
        ];
    }

    // -------------------------------------------------------
    // Accessors — dynamic real-time computed values
    // -------------------------------------------------------

    /**
     * Dynamically return 'Expired' whenever expiry_date has passed
     * and the stored status is still 'Active', without any DB write.
     */
    public function getStatusAttribute(string $value): string
    {
        if ($value === 'Active' && $this->expiry_date && $this->expiry_date->isPast()) {
            return 'Expired';
        }

        return $value;
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function ownerResident()
    {
        return $this->belongsTo(Resident::class, 'owner_resident_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function statusLogs()
    {
        return $this->hasMany(BusinessStatusLog::class, 'business_id')->orderBy('created_at');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Generate next permit number e.g. BP-2025-00001
    public static function generatePermitNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;

        return 'BP-'.$year.'-'.str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function isExpired(): bool
    {
        return ($this->expiry_date && $this->expiry_date->isPast()) || $this->status === 'Expired';
    }

    public function isActive(): bool
    {
        return $this->status === 'Active' && ! $this->isExpired();
    }

    public function isPendingPortal(): bool
    {
        return in_array($this->status, ['Pending', 'For Review']) && $this->source === 'portal';
    }

    // Badge color per status for the blade views
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Active'     => 'badge-green',
            'Expired'    => 'badge-red',
            'Suspended'  => 'badge-yellow',
            'Cancelled'  => 'badge-gray',
            'Pending'    => 'badge-yellow',
            'For Review' => 'badge-blue',
            default      => 'badge-gray',
        };
    }
}
