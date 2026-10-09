<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicStaff extends Model
{
    use Archivable;

    use HasFactory;

    protected $table = 'committee_clinic_staff';

    protected $fillable = [
        'full_name',
        'position',
        'specialization',
        'affiliation',
        'contact_number',
        'schedule',
        'status',
    ];
}
