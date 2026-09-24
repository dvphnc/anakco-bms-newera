<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStockLog extends Model
{
    protected static function booted(): void
    {
        // Task 1.1 — medicine handed out to a registered resident goes into their
        // transaction history.
        static::created(function (MedicineStockLog $log) {
            if ($log->adjustment_type !== 'out' || ! $log->beneficiary_resident_id) {
                return;
            }
            $resident = Resident::find($log->beneficiary_resident_id);
            if (! $resident) {
                return;
            }
            $medicine = $log->medicine;
            app(\App\Services\TransactionService::class)->logFromSource(
                $log,
                $resident,
                'medicine',
                trim(($medicine?->medicine_name ?? 'Medicine').($log->purpose ? " — {$log->purpose}" : '')),
                ['quantity' => $log->quantity, 'unit' => $medicine?->unit],
            );
        });
    }

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
