<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BpsoMember extends Model
{
    use HasFactory;

    protected $table = 'committee_bpso';

    protected $fillable = ['full_name','rank','badge_number','contact_number','assignment','status'];

}