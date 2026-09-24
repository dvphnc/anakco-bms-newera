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

    /** Set while adjust() saves, so the hook below doesn't log the change twice. */
    private bool $adjusting = false;

    protected static function booted(): void
    {
        // Every quantity change lands in the ledger, including stock added or
        // corrected by hand in the BDRRM tab.
        static::created(function (ReliefSupply $supply) {
            if ($supply->quantity > 0) {
                $supply->log($supply->quantity, 0, 'Received'.($supply->source ? " from {$supply->source}" : ''));
            }
        });

        static::updated(function (ReliefSupply $supply) {
            if (! $supply->adjusting && $supply->wasChanged('quantity')) {
                $before = (int) $supply->getOriginal('quantity');
                $supply->log($supply->quantity - $before, $before, 'Edited in the relief inventory');
            }
        });
    }

    public function programs()
    {
        return $this->belongsToMany(AssistanceProgram::class, 'assistance_program_supplies')
            ->withPivot('quantity_per_claim')
            ->withTimestamps();
    }

    public function movements()
    {
        return $this->hasMany(ReliefSupplyMovement::class);
    }

    /**
     * Change the stock by $change (negative = given out) and record why.
     * Callers lock the row first (lockForUpdate) inside a DB transaction.
     */
    public function adjust(int $change, string $reason, ?ResidentTransaction $transaction = null): void
    {
        $before = $this->quantity;
        $this->quantity = $before + $change;

        // Keep the status label in step with the count
        if ($this->quantity <= 0) {
            $this->status = 'Depleted';
        } elseif ($this->status === 'Depleted') {
            $this->status = 'Available';
        }

        $this->adjusting = true;
        try {
            $this->save();
        } finally {
            $this->adjusting = false;
        }

        $this->log($change, $before, $reason, $transaction);
    }

    /** e.g. "Rice — 40 sacks" */
    public function getLabelAttribute(): string
    {
        return trim("{$this->item_name} — ".number_format($this->quantity).' '.($this->unit ?? ''));
    }

    private function log(int $change, int $before, string $reason, ?ResidentTransaction $transaction = null): void
    {
        $this->movements()->create([
            'change'                  => $change,
            'stock_before'            => $before,
            'stock_after'             => $before + $change,
            'resident_transaction_id' => $transaction?->id,
            'reason'                  => $reason,
            'performed_by'            => auth()->id(),
        ]);
    }
}
