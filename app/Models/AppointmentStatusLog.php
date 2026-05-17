<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentStatusLog extends Model
{
    public $timestamps = false;

    protected $table    = 'appointment_status_logs';
    protected $fillable = ['appointment_id', 'from_status', 'to_status', 'changed_by', 'note'];
    protected $casts    = ['created_at' => 'datetime'];

    public function appointment()
    {
        return $this->belongsTo(DocumentAppointment::class);
    }

    public function iconClass(): string
    {
        return match ($this->to_status) {
            'Pending'    => 'fas fa-clock',
            'Confirmed'  => 'fas fa-circle-check',
            'Processing' => 'fas fa-gear',
            'Ready'      => 'fas fa-bell',
            'Released'   => 'fas fa-flag-checkered',
            'Cancelled'  => 'fas fa-ban',
            default      => 'fas fa-circle-dot',
        };
    }
}
