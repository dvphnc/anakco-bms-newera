<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefSupply extends Model
{
    use HasFactory;

    protected $table = 'committee_relief_supplies';

    protected $fillable = [
        'item_name',
        'category',
        'quantity',
        'unit',
        'source',
        'date_received',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date_received' => 'date',
        'quantity'      => 'integer',
    ];
}
