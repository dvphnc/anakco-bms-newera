<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Model;

/** One change to a relief supply's quantity — the stock ledger. */
class ReliefSupplyMovement extends Model
{
    use Archivable;

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
        return $this->belongsTo(ReliefSupply::class, 'relief_supply_id')->withTrashed();
    }

    public function transaction()
    {
        return $this->belongsTo(ResidentTransaction::class, 'resident_transaction_id')->withTrashed();
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by')->withTrashed();
    }
}
