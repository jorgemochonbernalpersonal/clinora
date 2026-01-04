<?php

namespace App\Mail;

use App\Core\Users\Models\Professional;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientLimitWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Professional $professional,
        public int $currentPatients,
        public int $limit
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 ¡Tu clínica está creciendo! - Clinora',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $percentage = round(($this->currentPatients / $this->limit) * 100);
        
        return new Content(
            view: 'emails.limits.warning',
            with: [
                'professional' => $this->professional,
                'currentPatients' => $this->currentPatients,
                'limit' => $this->limit,
                'percentage' => $percentage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
