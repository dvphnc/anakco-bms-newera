<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfraFinancial extends Model
{
    use HasFactory;

    protected $table = 'committee_infra_financials';

    protected $fillable = [
        'title',
        'type',
        'fund_source',
        'amount',
        'date',
        'reference_number',
        'remarks',
        'file_path',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];
}
