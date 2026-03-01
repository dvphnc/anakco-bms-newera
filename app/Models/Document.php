<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_number',
        'resident_id',
        'document_type',
        'purpose',
        'fee_paid',
        'status',
        'issued_by',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'fee_paid'    => 'decimal:2',
            'released_at' => 'date',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Generate next document number e.g. DOC-2025-00001
    public static function generateDocNumber(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;

        return 'DOC-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function isReleased(): bool
    {
        return $this->status === 'Released';
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }
}