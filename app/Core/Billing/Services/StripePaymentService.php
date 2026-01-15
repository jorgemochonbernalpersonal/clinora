<?php

namespace App\Core\Billing\Services;

use App\Core\Billing\Models\Invoice;
use Illuminate\Support\Facades\Log;

class StripePaymentService
{
    /**
     * Check if Stripe is enabled and configured
     */
    public function isEnabled(): bool
    {
        return config('billing.payment_gateways.stripe.enabled', false) 
            && !empty(config('billing.payment_gateways.stripe.secret_key'))
            && !empty(config('billing.payment_gateways.stripe.public_key'));
    }

    /**
     * Create Stripe Checkout Session for invoice payment
     */
    public function createCheckoutSession(Invoice $invoice, string $successUrl, string $cancelUrl): ?array
    {
        if (!$this->isEnabled()) {
            Log::warning('Stripe payment attempted but not enabled', [
                'invoice_id' => $invoice->id,
            ]);
            return null;
        }

        try {
            // Check if Stripe PHP SDK is available
            if (!class_exists(\Stripe\Stripe::class)) {
                Log::error('Stripe PHP SDK not installed. Run: composer require stripe/stripe-php');
                return null;
            }

            \Stripe\Stripe::setApiKey(config('billing.payment_gateways.stripe.secret_key'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($invoice->currency),
                        'product_data' => [
                            'name' => 'Factura ' . $invoice->invoice_number,
                            'description' => $this->getInvoiceDescription($invoice),
                        ],
                        'unit_amount' => $this->convertToCents($invoice->total),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'client_reference_id' => (string) $invoice->id,
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'professional_id' => $invoice->professional_id,
                ],
                'customer_email' => $invoice->contact->email ?? null,
            ]);

            return [
                'session_id' => $session->id,
                'url' => $session->url,
            ];
        } catch (\Exception $e) {
            Log::error('Error creating Stripe checkout session', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify payment session and mark invoice as paid
     */
    public function verifyPayment(string $sessionId): ?Invoice
    {
        if (!$this->isEnabled()) {
            return null;
        }

        try {
            if (!class_exists(\Stripe\Stripe::class)) {
                return null;
            }

            \Stripe\Stripe::setApiKey(config('billing.payment_gateways.stripe.secret_key'));

            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return null;
            }

            $invoiceId = $session->metadata->invoice_id ?? null;
            if (!$invoiceId) {
                Log::warning('Stripe session missing invoice_id metadata', [
                    'session_id' => $sessionId,
                ]);
                return null;
            }

            $invoice = Invoice::find($invoiceId);
            if (!$invoice) {
                Log::warning('Invoice not found for Stripe payment', [
                    'invoice_id' => $invoiceId,
                    'session_id' => $sessionId,
                ]);
                return null;
            }

            // Mark invoice as paid
            $invoice->markAsPaid();
            
            // Store payment information
            $invoice->update([
                'stripe_session_id' => $sessionId,
                'stripe_payment_intent_id' => $session->payment_intent ?? null,
            ]);

            Log::info('Invoice marked as paid via Stripe', [
                'invoice_id' => $invoice->id,
                'session_id' => $sessionId,
            ]);

            return $invoice;
        } catch (\Exception $e) {
            Log::error('Error verifying Stripe payment', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get invoice description for Stripe
     */
    private function getInvoiceDescription(Invoice $invoice): string
    {
        $items = $invoice->items->pluck('description')->take(3)->implode(', ');
        return 'Servicios de psicología clínica - ' . $items;
    }

    /**
     * Convert amount to cents (Stripe uses smallest currency unit)
     */
    private function convertToCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Get Stripe public key
     */
    public function getPublicKey(): ?string
    {
        return config('billing.payment_gateways.stripe.public_key');
    }
}
