<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'position',
        'committee',
        'contact_number',
        'photo_path',
        'term_start',
        'term_end',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'term_start' => 'date',
            'term_end' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function getTermDurationAttribute(): string
    {
        return $this->term_start->format('Y').' – '.$this->term_end->format('Y');
    }

    public function isCurrentlyServing(): bool
    {
        $now = now();

        return $this->term_start->lte($now) && $this->term_end->gte($now);
    }
}
