<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    use HasFactory;

    protected $table = 'committee_scholars';

    protected $fillable = ['full_name', 'school', 'course_grade_level', 'scholarship_type', 'year_level', 'grant_amount', 'status', 'start_date'];

    protected $casts = ['start_date' => 'date'];
}
