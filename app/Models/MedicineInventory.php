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
        'brand_name',
        'generic_name',
        'category',
        'dosage_form',
        'unit',
        'current_stock',
        'reorder_level',
        'expiry_date',
        'supplier',
        'batch_number',
        'barcode',
    ];

    protected $casts = [
        'expiry_date'   => 'date',
        'current_stock' => 'integer',
        'reorder_level' => 'integer',
    ];

    public function stockLogs()
    {
        return $this->hasMany(MedicineStockLog::class, 'medicine_id')->latest();
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 60): bool
    {
        return $this->expiry_date
            && ! $this->expiry_date->isPast()
            && $this->expiry_date->diffInDays(now()) <= $days;
    }
}
