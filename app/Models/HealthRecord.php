<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthRecord extends Model
{
    use HasFactory;

    protected $table = 'committee_health_records';

    protected $fillable = ['patient_name','age','gender','address','diagnosis','program','visit_date','attended_by','notes'];

    protected $casts = ['visit_date'=>'date'];
}