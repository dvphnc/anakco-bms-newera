<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyLog extends Model
{
    use HasFactory;

    protected $table = 'committee_emergency_logs';

    protected $fillable = ['incident_type','incident_date','location','affected_families','affected_persons','description','response_actions','reported_by','status'];

    protected $casts = ['incident_date'=>'date'];
}