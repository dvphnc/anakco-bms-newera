<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenter extends Model
{
    use HasFactory;

    protected $table = 'committee_evacuation_centers';

    protected $fillable = ['center_name', 'location', 'capacity', 'current_occupancy', 'status', 'contact_person', 'contact_number', 'facilities'];
}
