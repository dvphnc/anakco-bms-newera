<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatrolLog extends Model
{
    use HasFactory;

    protected $table = 'committee_patrol_logs';

    protected $fillable = ['patrol_date','shift','area_covered','personnel_count','findings','reported_by'];

    protected $casts = ['patrol_date'=>'date'];
}