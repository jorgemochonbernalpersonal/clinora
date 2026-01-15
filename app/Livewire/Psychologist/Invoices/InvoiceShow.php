<?php

namespace App\Livewire\Psychologist\Invoices;

use App\Core\Billing\Models\Invoice;
use App\Core\Billing\Services\InvoiceService;
use App\Core\Billing\Services\StripePaymentService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class InvoiceShow extends Component
{
    public Invoice $invoice;
    public $isLoading = false;

    public function mount($id)
    {
        $invoice = Invoice::with(['items', 'professional', 'contact', 'appointment'])
            ->find($id);
        
        if (!$invoice || $invoice->professional_id !== auth()->user()->professional->id) {
            session()->flash('error', 'Factura no encontrada');
            return redirect()->route('psychologist.invoices.index');
        }

        $this->invoice = $invoice;
    }

    public function sendInvoice()
    {
        $this->isLoading = true;
        
        try {
            $invoiceService = app(InvoiceService::class);
            $this->invoice = $invoiceService->sendInvoice($this->invoice->id);
            session()->flash('success', 'Factura marcada como enviada');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al enviar la factura: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function sendInvoiceByEmail()
    {
        $this->isLoading = true;
        
        try {
            // Check if contact has email
            if (!$this->invoice->contact->email) {
                session()->flash('error', 'El paciente no tiene email registrado');
                $this->isLoading = false;
                return;
            }

            // Generate PDF and XML if not exists
            $invoiceService = app(InvoiceService::class);
            $this->invoice = $invoiceService->sendInvoice($this->invoice->id);
            
            // Send email
            \Mail::to($this->invoice->contact->email)
                ->send(new \App\Mail\InvoiceSent($this->invoice));
            
            session()->flash('success', 'Factura enviada por email a ' . $this->invoice->contact->email);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al enviar la factura por email: ' . $e->getMessage());
            \Log::error('Error sending invoice email', [
                'invoice_id' => $this->invoice->id,
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function markAsPaid()
    {
        $this->isLoading = true;
        
        try {
            $invoiceService = app(InvoiceService::class);
            $this->invoice = $invoiceService->markAsPaid($this->invoice->id);
            session()->flash('success', 'Factura marcada como pagada');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al marcar la factura: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function cancelInvoice()
    {
        if (!$this->invoice->canBeCancelled()) {
            session()->flash('error', 'Esta factura no puede ser cancelada');
            return;
        }

        $this->isLoading = true;
        
        try {
            $invoiceService = app(InvoiceService::class);
            $this->invoice = $invoiceService->cancelInvoice($this->invoice->id);
            session()->flash('success', 'Factura cancelada correctamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cancelar la factura: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function downloadPdf()
    {
        return redirect()->route('psychologist.invoices.pdf', $this->invoice->id);
    }

    public function payWithStripe()
    {
        $this->isLoading = true;
        
        try {
            $paymentService = app(StripePaymentService::class);
            
            if (!$paymentService->isEnabled()) {
                session()->flash('error', 'Los pagos con tarjeta no están disponibles en este momento');
                $this->isLoading = false;
                return;
            }

            $successUrl = route('psychologist.invoices.payment.success', ['id' => $this->invoice->id]);
            $cancelUrl = route('psychologist.invoices.show', ['id' => $this->invoice->id]);

            $session = $paymentService->createCheckoutSession($this->invoice, $successUrl, $cancelUrl);

            if (!$session) {
                session()->flash('error', 'Error al crear la sesión de pago. Por favor, intenta de nuevo.');
                $this->isLoading = false;
                return;
            }

            // Redirect to Stripe Checkout
            return redirect($session['url']);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar el pago: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function getStripeEnabledProperty()
    {
        $paymentService = app(StripePaymentService::class);
        return $paymentService->isEnabled();
    }

    public function render()
    {
        return view('livewire.psychologist.invoices.invoice-show');
    }
}
