<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfraContract extends Model
{
    use HasFactory;

    protected $table = 'committee_infra_contracts';

    protected $fillable = [
        'contract_number',
        'contractor_name',
        'scope_of_work',
        'contract_amount',
        'start_date',
        'end_date',
        'status',
        'file_path',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'contract_amount' => 'decimal:2',
    ];
}
