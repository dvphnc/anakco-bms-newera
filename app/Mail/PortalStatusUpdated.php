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
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'document' => 'Your Document Request — Status Updated',
            'blotter'  => 'Your Blotter Report — Status Updated',
            'business' => 'Your Business Permit Request — Status Updated',
        ];

        return new Envelope(
            subject: $subjects[$this->type] ?? 'Your Portal Request — Status Updated',
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
