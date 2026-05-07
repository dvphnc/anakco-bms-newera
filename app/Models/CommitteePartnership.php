<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteePartnership extends Model
{
    use HasFactory;

    protected $table = 'committee_partnerships';

    protected $fillable = [
        'committee_slug',
        'partner_name',
        'partner_type',
        'mou_date',
        'validity_date',
        'contact_person',
        'contact_number',
        'description',
        'file_path',
    ];

    protected $casts = [
        'mou_date'      => 'date',
        'validity_date' => 'date',
    ];
}
