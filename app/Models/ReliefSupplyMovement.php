<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One change to a relief supply's quantity — the stock ledger. */
class ReliefSupplyMovement extends Model
{
    protected $fillable = [
        'relief_supply_id', 'change', 'stock_before', 'stock_after',
        'resident_transaction_id', 'reason', 'performed_by',
    ];

    protected $casts = [
        'change'       => 'integer',
        'stock_before' => 'integer',
        'stock_after'  => 'integer',
    ];

    public function supply()
    {
        return $this->belongsTo(ReliefSupply::class, 'relief_supply_id');
    }

    public function transaction()
    {
        return $this->belongsTo(ResidentTransaction::class, 'resident_transaction_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
