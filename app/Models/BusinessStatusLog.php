<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessStatusLog extends Model
{
    public $timestamps = false;

    protected $table    = 'business_status_logs';
    protected $fillable = ['business_id', 'from_status', 'to_status', 'changed_by', 'note'];
    protected $casts    = ['created_at' => 'datetime'];
}
