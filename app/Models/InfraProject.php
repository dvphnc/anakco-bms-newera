<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfraProject extends Model
{
    use HasFactory;

    protected $table = 'committee_projects';

    protected $fillable = ['project_name','project_type','location','budget','actual_cost','start_date','end_date','completion_percentage','status','remarks'];

    protected $casts = ['start_date'=>'date','end_date'=>'date'];
}