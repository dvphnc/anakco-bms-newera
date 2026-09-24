<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Task 1.1 — a document released to a registered resident goes into their
        // transaction history (once per document). Separate created/updated hooks
        // because wasRecentlyCreated never resets on the model object.
        $record = function (Document $doc) {
            if ($doc->status !== 'Released' || ! $doc->resident_id || ! ($resident = $doc->resident)) {
                return;
            }
            app(\App\Services\TransactionService::class)->logFromSource(
                $doc,
                $resident,
                'document',
                trim($doc->document_type.' '.($doc->doc_number ? "({$doc->doc_number})" : '')),
                [
                    'amount'        => $doc->fee_paid > 0 ? $doc->fee_paid : null,
                    'transacted_at' => $doc->released_at ?? now(),
                    'processed_by'  => $doc->released_by_user_id ?? $doc->issued_by ?? auth()->id(),
                ],
            );
        };

        static::created($record);
        static::updated(function (Document $doc) use ($record) {
            if ($doc->wasChanged(['status', 'resident_id'])) {
                $record($doc);
            }
        });
    }

    /**
     * Full status set — mirrors DocumentAppointment::$statuses for 1-to-1 sync.
     * Walk-in documents typically use Pending → Processing → Released / Cancelled.
     * Portal documents cycle through all six stages.
     */
    public static array $statuses = [
        'Pending', 'Processing', 'Ready', 'Released', 'Cancelled',
    ];

    protected $fillable = [
        'doc_number',
        'appointment_id',
        'source',
        'resident_id',
        'resident_name_portal',
        'requestor_name',
        'requestor_relationship',
        'requestor_contact',
        'document_type',
        'purpose',
        'fee_paid',
        'or_number',
        'status',
        'issued_by',
        'released_at',
        'released_to',
        'released_by_user_id',
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

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by_user_id');
    }

    /**
     * The scheduling record this document was created from (portal only).
     */
    public function appointment()
    {
        return $this->belongsTo(DocumentAppointment::class, 'appointment_id');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    /**
     * Generate a sequential Official Receipt number: OR-YYYY-NNNNN
     * Only called when fee_paid > 0 and no OR number was manually supplied.
     */
    public static function generateOrNumber(): string
    {
        $year   = date('Y');
        $prefix = 'OR-'.$year.'-';

        $max = self::where('or_number', 'like', $prefix.'%')
            ->selectRaw('MAX(CAST(SUBSTRING(or_number, ?) AS UNSIGNED)) as max_seq', [strlen($prefix) + 1])
            ->value('max_seq');

        $next = ($max ?? 0) + 1;

        return $prefix.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public static function generateDocNumber(): string
    {
        $year   = date('Y');
        $prefix = 'DOC-'.$year.'-';

        // Use MAX on the numeric suffix so gaps from deletions never cause collisions
        $max = self::where('doc_number', 'like', $prefix.'%')
            ->selectRaw('MAX(CAST(SUBSTRING(doc_number, ?) AS UNSIGNED)) as max_seq', [strlen($prefix) + 1])
            ->value('max_seq');

        $next = ($max ?? 0) + 1;

        return $prefix.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display name — resolves portal submissions that have no resident record.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->resident) {
            return $this->resident->last_name.', '.$this->resident->first_name;
        }

        return $this->resident_name_portal ?? '—';
    }

    public function isReleased(): bool
    {
        return $this->status === 'Released';
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isPortal(): bool
    {
        return $this->source === 'portal';
    }
}
