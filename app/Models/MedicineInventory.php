<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineInventory extends Model
{
    use HasFactory;

    protected $table = 'committee_medicine_inventory';

    protected $fillable = [
        'medicine_name',
        'generic_name',
        'unit',
        'current_stock',
        'reorder_level',
        'expiry_date',
        'supplier',
        'batch_number',
    ];

    protected $casts = [
        'expiry_date'   => 'date',
        'current_stock' => 'integer',
        'reorder_level' => 'integer',
    ];

    /**
     * Whether the stock is at or below reorder level.
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    /**
     * Whether the medicine is expired (expiry_date in the past).
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
