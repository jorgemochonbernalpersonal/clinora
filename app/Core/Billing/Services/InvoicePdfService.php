<?php

namespace App\Core\Billing\Services;

use App\Core\Billing\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoicePdfService
{
    /**
     * Generate PDF for invoice
     */
    public function generatePdf(Invoice $invoice): string
    {
        $invoice->load(['items', 'professional.user', 'contact', 'appointment']);
        
        $pdf = Pdf::loadView('billing.invoices.pdf', [
            'invoice' => $invoice,
            'professional' => $invoice->professional,
            'user' => $invoice->professional->user,
            'contact' => $invoice->contact,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('defaultFont', 'DejaVu Sans');

        $filename = $this->getPdfFilename($invoice);
        $path = config('billing.invoice.storage_path', 'invoices') . '/' . $filename;
        
        // Ensure directory exists
        $disk = Storage::disk(config('billing.invoice.storage_disk', 'local'));
        $disk->put($path, $pdf->output());

        return $path;
    }

    /**
     * Get PDF filename
     */
    private function getPdfFilename(Invoice $invoice): string
    {
        return sprintf(
            'factura_%s_%s.pdf',
            $invoice->invoice_number,
            $invoice->issue_date->format('Y-m-d')
        );
    }

    /**
     * Generate PDF and return as download response
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['items', 'professional.user', 'contact', 'appointment']);
        
        $pdf = Pdf::loadView('billing.invoices.pdf', [
            'invoice' => $invoice,
            'professional' => $invoice->professional,
            'user' => $invoice->professional->user,
            'contact' => $invoice->contact,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('defaultFont', 'DejaVu Sans');

        $filename = sprintf(
            'factura-%s.pdf',
            $invoice->invoice_number
        );

        return $pdf->download($filename);
    }

    /**
     * Generate PDF and return as stream (for viewing in browser)
     */
    public function streamPdf(Invoice $invoice)
    {
        $invoice->load(['items', 'professional.user', 'contact', 'appointment']);
        
        $pdf = Pdf::loadView('billing.invoices.pdf', [
            'invoice' => $invoice,
            'professional' => $invoice->professional,
            'user' => $invoice->professional->user,
            'contact' => $invoice->contact,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('defaultFont', 'DejaVu Sans');

        $filename = sprintf(
            'factura-%s.pdf',
            $invoice->invoice_number
        );

        return $pdf->stream($filename);
    }
}
