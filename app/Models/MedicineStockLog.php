<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStockLog extends Model
{
    protected $table = 'medicine_stock_logs';

    protected $fillable = [
        'medicine_id',
        'adjustment_type',
        'quantity',
        'stock_before',
        'stock_after',
        'reason',
        'performed_by',
        'beneficiary_name',
        'beneficiary_resident_id',
        'purpose',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'stock_before' => 'integer',
        'stock_after'  => 'integer',
    ];

    public function medicine()
    {
        return $this->belongsTo(MedicineInventory::class, 'medicine_id');
    }

    public function beneficiaryResident()
    {
        return $this->belongsTo(\App\Models\Resident::class, 'beneficiary_resident_id');
    }
}
