<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentAppointment extends Model
{
    use HasFactory;

    protected $table = 'document_appointments';

    protected $fillable = [
        'appointment_number',
        'source',
        'resident_name',
        'requestor_name',
        'requestor_relationship',
        'requestor_contact',
        'contact_number',
        'email',
        'document_type',
        'purpose',
        'preferred_date',
        'pickup_date',
        'status',
        'notes',
        'processed_by',
        'released_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'pickup_date'    => 'date',
        'released_at'    => 'datetime',
    ];

    /**
     * Status options.
     */
    public static array $statuses = [
        'Pending',
        'Processing',
        'Ready',
        'Released',
        'Cancelled',
    ];

    /**
     * Document types available via portal.
     */
    public static array $documentTypes = [
        'Barangay Clearance',
        'Certificate of Indigency',
        'Certificate of Residency',
        'Business Clearance',
        'Certificate of Good Moral Character',
        'Barangay ID',
        'Other',
    ];

    /**
     * Generate a unique appointment number like APT-20260507-XXXX.
     */
    public static function generateNumber(): string
    {
        do {
            $number = 'APT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (self::where('appointment_number', $number)->exists());

        return $number;
    }

    public function statusLogs()
    {
        return $this->hasMany(AppointmentStatusLog::class, 'appointment_id')->orderBy('created_at');
    }

    /**
     * The Document record mirroring this appointment's lifecycle (portal submissions only).
     */
    public function document()
    {
        return $this->hasOne(Document::class, 'appointment_id');
    }

    /**
     * Status badge color helper.
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            'Pending'    => 'var(--gold)',
            'Confirmed'  => 'var(--navy)',
            'Processing' => '#2563eb',
            'Ready'      => '#16a34a',
            'Released'   => '#6b7280',
            'Cancelled'  => 'var(--crimson)',
            default      => '#6b7280',
        };
    }
}
