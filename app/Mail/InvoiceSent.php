<?php

namespace App\Mail;

use App\Core\Billing\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceSent extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice
    ) {
        $this->invoice->load(['items', 'professional.user', 'contact', 'appointment']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura ' . $this->invoice->invoice_number . ' - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoices.sent',
            with: [
                'invoice' => $this->invoice,
                'professional' => $this->invoice->professional,
                'user' => $this->invoice->professional->user,
                'contact' => $this->invoice->contact,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // Generate PDF if not exists
        if (!$this->invoice->pdf_path) {
            $pdfService = app(\App\Core\Billing\Services\InvoicePdfService::class);
            $pdfPath = $pdfService->generatePdf($this->invoice);
            $this->invoice->update(['pdf_path' => $pdfPath]);
        }
        
        // Attach PDF
        if ($this->invoice->pdf_path) {
            $disk = \Illuminate\Support\Facades\Storage::disk(config('billing.invoice.storage_disk', 'local'));
            if ($disk->exists($this->invoice->pdf_path)) {
                $attachments[] = Attachment::fromStorageDisk(
                    config('billing.invoice.storage_disk', 'local'),
                    $this->invoice->pdf_path
                )->as('factura-' . $this->invoice->invoice_number . '.pdf');
            }
        }
        
        return $attachments;
    }
}
