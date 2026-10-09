<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreetSweeper extends Model
{
    use Archivable;

    use HasFactory;

    protected $table = 'committee_street_sweepers';

    protected $fillable = [
        'full_name',
        'assigned_zone',
        'contact_number',
        'schedule',
        'date_assigned',
        'status',
    ];

    protected $casts = [
        'date_assigned' => 'date',
    ];
}
