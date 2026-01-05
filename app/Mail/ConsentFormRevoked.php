<?php

namespace App\Mail;

use App\Core\ConsentForms\Models\ConsentForm;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsentFormRevoked extends Mailable
{
    use Queueable, SerializesModels;

    public ConsentForm $consentForm;
    public ?string $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(ConsentForm $consentForm, ?string $reason = null)
    {
        $this->consentForm = $consentForm;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso: Consentimiento Informado Revocado - Clinora',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.consent-revoked',
            with: [
                'consentForm' => $this->consentForm,
                'patientName' => $this->consentForm->contact->full_name,
                'professionalName' => $this->consentForm->professional->user->name ?? 'Tu profesional',
                'revokedDate' => $this->consentForm->revoked_at->format('d/m/Y'),
                'reason' => $this->reason,
                'documentNumber' => 'CL-' . date('Y') . '-' . str_pad($this->consentForm->id, 6, '0', STR_PAD_LEFT),
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
