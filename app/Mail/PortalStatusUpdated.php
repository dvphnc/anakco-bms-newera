<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PortalStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $type          'document' | 'blotter' | 'business'
     * @param  string  $requestNumber e.g. APT-20260518-AB12
     * @param  string  $residentName
     * @param  string  $newStatus
     * @param  string|null  $notes    Admin note / instructions
     * @param  string|null  $preferredDate  For document appointments
     */
    public function __construct(
        public readonly string  $type,
        public readonly string  $requestNumber,
        public readonly string  $residentName,
        public readonly string  $newStatus,
        public readonly ?string $notes = null,
        public readonly ?string $preferredDate = null,
        public readonly ?float  $feePaid = null,
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = match($this->newStatus) {
            'Submitted'  => 'Request Received',
            'Processing' => 'Now Processing',
            'Ready'      => 'Ready for Pick-up',
            'Released'   => 'Released ✔',
            'Cancelled'  => 'Request Cancelled',
            default      => 'Status Updated',
        };

        $typeLabel = match($this->type) {
            'blotter'  => 'Blotter Report',
            'business' => 'Business Permit',
            default    => 'Document Request',
        };

        return new Envelope(
            subject: '[Barangay New Era] '.$typeLabel.' — '.$statusLabel.' ('.$this->requestNumber.')',
            from: config('mail.from.address', 'noreply@barangaynewera.gov.ph'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.portal-status-updated',
        );
    }
}
