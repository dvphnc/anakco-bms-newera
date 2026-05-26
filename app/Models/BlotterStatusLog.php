<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterStatusLog extends Model
{
    public $timestamps = false;

    protected $table    = 'blotter_status_logs';
    protected $fillable = ['blotter_case_id', 'from_status', 'to_status', 'changed_by', 'note'];
    protected $casts    = ['created_at' => 'datetime'];
}
