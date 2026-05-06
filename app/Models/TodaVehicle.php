<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodaVehicle extends Model
{
    use HasFactory;

    protected $table = 'committee_toda';

    protected $fillable = ['operator_name', 'driver_name', 'vehicle_type', 'plate_number', 'toda_name', 'route', 'registration_date', 'expiry_date', 'status'];

    protected $casts = ['registration_date' => 'date', 'expiry_date' => 'date'];
}
