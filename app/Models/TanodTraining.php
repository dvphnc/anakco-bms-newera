<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanodTraining extends Model
{
    use HasFactory;

    protected $table = 'committee_tanod_trainings';

    protected $fillable = [
        'title',
        'training_type',
        'training_date',
        'duration',
        'venue',
        'facilitator',
        'participants_count',
        'notes',
        'file_path',
    ];

    protected $casts = [
        'training_date' => 'date',
    ];
}
