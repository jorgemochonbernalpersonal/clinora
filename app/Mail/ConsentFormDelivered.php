<?php

namespace App\Mail;

use App\Core\ConsentForms\Models\ConsentForm;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsentFormDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public ConsentForm $consentForm;

    /**
     * Create a new message instance.
     */
    public function __construct(ConsentForm $consentForm)
    {
        $this->consentForm = $consentForm;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Consentimiento Informado - Clinora',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.consent-delivered',
            with: [
                'consentForm' => $this->consentForm,
                'patientName' => $this->consentForm->contact->full_name,
                'professionalName' => $this->consentForm->professional->user->name ?? 'Tu profesional',
                'signedDate' => $this->consentForm->signed_at->format('d/m/Y'),
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
        // Generar PDF
        $consentBodyContent = $this->getConsentBodyContent();
        
        $pdf = Pdf::loadView('modules.psychology.consent-forms.consent-form-pdf', [
            'consentForm' => $this->consentForm,
            'consentBodyContent' => $consentBodyContent,
        ])
        ->setPaper('a4')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true);
        
        $filename = 'consentimiento_' . 
                    \Str::slug($this->consentForm->contact->full_name) . '_' .
                    now()->format('Y-m-d') . 
                    '.pdf';

        // Log para verificar que se está adjuntando el PDF
        \Log::info('Adjuntando PDF al email de consentimiento', [
            'consent_form_id' => $this->consentForm->id,
            'filename' => $filename,
            'patient_email' => $this->consentForm->contact->email,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }

    /**
     * Extract body content from full HTML document
     */
    private function getConsentBodyContent(): string
    {
        $html = $this->consentForm->consent_text;
        
        if (empty($html)) {
            return '';
        }
        
        // If the HTML contains a full document structure, extract only the body content
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $bodyContent = trim($matches[1]);
            // Remove any remaining DOCTYPE, html, head tags that might be nested
            $bodyContent = preg_replace('/<! DOCTYPE[^>]*>/i', '', $bodyContent);
            $bodyContent = preg_replace('/<html[^>]*>/i', '', $bodyContent);
            $bodyContent = preg_replace('/<\/html>/i', '', $bodyContent);
            $bodyContent = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $bodyContent);
            return $bodyContent;
        }
        
        // If it's already just body content, return as is (but clean up any stray tags)
        $html = preg_replace('/<! DOCTYPE[^>]*>/i', '', $html);
        $html = preg_replace('/<html[^>]*>/i', '', $html);
        $html = preg_replace('/<\/html>/i', '', $html);
        $html = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $html);
        $html = preg_replace('/<body[^>]*>/i', '', $html);
        $html = preg_replace('/<\/body>/i', '', $html);
        
        return trim($html);
    }
}
