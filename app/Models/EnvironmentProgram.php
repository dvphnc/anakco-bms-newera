<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnvironmentProgram extends Model
{
    use HasFactory;

    protected $table = 'committee_environment_programs';

    protected $fillable = ['program_name','program_type','program_date','location','volunteers','trees_planted','waste_collected_kg','status','notes'];

    protected $casts = ['program_date'=>'date'];
}