<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivelihoodBeneficiary extends Model
{
    use HasFactory;

    protected $table = 'committee_livelihood_beneficiaries';

    protected $fillable = ['full_name','address','contact_number','program_name','program_type','date_enrolled','amount_received','status','remarks'];

    protected $casts = ['date_enrolled'=>'date'];
}