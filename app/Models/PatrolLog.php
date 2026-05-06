<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolLog extends Model
{
    use HasFactory;

    protected $table = 'committee_patrol_logs';

    protected $fillable = ['patrol_date', 'shift', 'area_covered', 'personnel_count', 'findings', 'reported_by'];

    protected $casts = ['patrol_date' => 'date'];
}
