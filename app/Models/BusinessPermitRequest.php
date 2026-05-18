<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessPermitRequest extends Model
{
    protected $fillable = [
        'request_number', 'owner_name', 'contact_number', 'email',
        'business_name', 'business_type', 'business_address',
        'operation_year', 'purpose', 'status', 'notes', 'processed_by',
    ];

    public static array $statuses = [
        'Pending', 'Under Review', 'For Inspection', 'Approved', 'Rejected', 'Cancelled',
    ];

    public static array $businessTypes = [
        'Retail / Sari-sari Store',
        'Food & Beverage',
        'Salon / Barbershop',
        'Repair Shop',
        'Pharmacy / Drug Store',
        'Clinic / Medical',
        'Printing / Photocopying',
        'Online Selling / E-commerce',
        'Transportation / Logistics',
        'Construction / Contractor',
        'Other',
    ];

    public static function generateNumber(): string
    {
        do {
            $num = 'BPR-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        } while (static::where('request_number', $num)->exists());

        return $num;
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'Pending'       => 'var(--gold)',
            'Under Review'  => '#3a5fa0',
            'For Inspection'=> '#a05828',
            'Approved'      => '#2e6b47',
            'Rejected'      => '#8b2e2e',
            'Cancelled'     => '#6b7280',
            default         => '#6b7280',
        };
    }
}
