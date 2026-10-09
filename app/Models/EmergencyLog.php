<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyLog extends Model
{
    use Archivable;

    use HasFactory;

    protected $table = 'committee_emergency_logs';

    protected $fillable = ['incident_type', 'incident_date', 'location', 'affected_families', 'affected_persons', 'description', 'response_actions', 'reported_by', 'status'];

    protected $casts = ['incident_date' => 'date'];
}
