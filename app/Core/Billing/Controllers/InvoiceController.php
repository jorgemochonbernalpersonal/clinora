<?php

namespace App\Core\Billing\Controllers;

use App\Core\Appointments\Models\Appointment;
use App\Core\Billing\Requests\StoreInvoiceRequest;
use App\Core\Billing\Requests\UpdateInvoiceRequest;
use App\Core\Billing\Services\InvoiceService;
use App\Core\Billing\Services\InvoicePdfService;
use App\Core\Billing\Services\StripePaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService
    ) {}

    /**
     * List invoices
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            $filters = [
                'status' => $request->input('status'),
                'contact_id' => $request->input('contact_id'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
                'overdue' => $request->boolean('overdue'),
            ];

            $invoices = $this->invoiceService->getInvoicesForProfessional(
                $professional,
                array_filter($filters),
                true,
                $request->input('per_page', 50)
            );

            return response()->json([
                'success' => true,
                'data' => $invoices->items(),
                'meta' => [
                    'current_page' => $invoices->currentPage(),
                    'last_page' => $invoices->lastPage(),
                    'per_page' => $invoices->perPage(),
                    'total' => $invoices->total(),
                ],
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al listar facturas', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las facturas',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Create invoice from appointment
     */
    public function createFromAppointment(Request $request, int $appointmentId): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            $appointment = Appointment::findOrFail($appointmentId);

            // Verify appointment belongs to professional
            if ($appointment->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cita no pertenece a tu práctica',
                ], 403);
            }

            // Check if invoice already exists
            if ($appointment->invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una factura para esta cita',
                ], 409);
            }

            $invoice = $this->invoiceService->createFromAppointment(
                $appointment,
                $request->user()->id
            );

            $this->logUserAction('Factura creada desde cita', [
                'invoice_id' => $invoice->id,
                'appointment_id' => $appointment->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Factura creada exitosamente',
                'data' => $invoice,
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al crear factura desde cita', $e, [
                'appointment_id' => $appointmentId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Create manual invoice
     */
    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            $contact = \App\Core\Contacts\Models\Contact::findOrFail($request->validated()['contact_id']);
            
            // Verify contact belongs to professional
            if ($contact->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El paciente no pertenece a tu práctica',
                ], 403);
            }

            $invoice = $this->invoiceService->createManualInvoice(
                $professional,
                $request->validated()['contact_id'],
                $request->validated(),
                $request->user()->id
            );

            $this->logUserAction('Factura manual creada', [
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Factura creada exitosamente',
                'data' => $invoice,
            ], 201);
        } catch (\Exception $e) {
            $this->logError('Error al crear factura manual', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Show invoice
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Factura no encontrada',
                ], 404);
            }

            $invoice = $this->invoiceService->getInvoice($id);

            return response()->json([
                'success' => true,
                'data' => $invoice,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al obtener factura', $e, [
                'invoice_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la factura',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Update invoice
     */
    public function update(UpdateInvoiceRequest $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Factura no encontrada',
                ], 404);
            }

            $invoice = $this->invoiceService->updateInvoice(
                $id,
                $request->validated(),
                $request->user()->id
            );

            $this->logUserAction('Factura actualizada', [
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Factura actualizada exitosamente',
                'data' => $invoice,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al actualizar factura', $e, [
                'invoice_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?? 'Error al actualizar la factura',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Send invoice
     */
    public function send(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Factura no encontrada',
                ], 404);
            }

            $invoice = $this->invoiceService->sendInvoice($id);

            $this->logUserAction('Factura enviada', [
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Factura enviada exitosamente',
                'data' => $invoice,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al enviar factura', $e, [
                'invoice_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar la factura',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markPaid(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Factura no encontrada',
                ], 404);
            }

            $invoice = $this->invoiceService->markAsPaid($id);

            $this->logUserAction('Factura marcada como pagada', [
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Factura marcada como pagada',
                'data' => $invoice,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al marcar factura como pagada', $e, [
                'invoice_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al marcar la factura como pagada',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Cancel invoice
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Factura no encontrada',
                ], 404);
            }

            $invoice = $this->invoiceService->cancelInvoice($id);

            $this->logUserAction('Factura cancelada', [
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Factura cancelada exitosamente',
                'data' => $invoice,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al cancelar factura', $e, [
                'invoice_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?? 'Error al cancelar la factura',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Download invoice PDF
     */
    public function downloadPdf(Request $request, int $id)
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                abort(404, 'Factura no encontrada');
            }

            $invoice = $this->invoiceService->getInvoice($id);
            $pdfService = app(InvoicePdfService::class);

            // Generate PDF if not exists
            if (!$invoice->pdf_path || !Storage::disk(config('billing.invoice.storage_disk', 'local'))->exists($invoice->pdf_path)) {
                $pdfPath = $pdfService->generatePdf($invoice);
                $invoice->update(['pdf_path' => $pdfPath]);
            }

            return $pdfService->downloadPdf($invoice);
        } catch (\Exception $e) {
            $this->logError('Error al descargar PDF de factura', $e, [
                'invoice_id' => $id,
            ]);

            abort(500, 'Error al descargar el PDF');
        }
    }

    /**
     * Get invoice statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            $fromDate = $request->input('from_date') 
                ? \Carbon\Carbon::parse($request->input('from_date'))
                : null;
            
            $toDate = $request->input('to_date')
                ? \Carbon\Carbon::parse($request->input('to_date'))
                : null;

            $stats = $this->invoiceService->getStatistics($professional, $fromDate, $toDate);

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al obtener estadísticas de facturas', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Create Stripe checkout session for invoice payment
     */
    public function createPaymentSession(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Factura no encontrada',
                ], 404);
            }

            $invoice = $this->invoiceService->getInvoice($id);

            // Check if invoice can be paid
            if ($invoice->status->value === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta factura ya está pagada',
                ], 400);
            }

            if ($invoice->status->value === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta factura está cancelada',
                ], 400);
            }

            $paymentService = app(StripePaymentService::class);

            if (!$paymentService->isEnabled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los pagos con tarjeta no están disponibles en este momento',
                ], 503);
            }

            $successUrl = route('psychologist.invoices.payment.success', ['id' => $invoice->id]);
            $cancelUrl = route('psychologist.invoices.show', ['id' => $invoice->id]);

            $session = $paymentService->createCheckoutSession($invoice, $successUrl, $cancelUrl);

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la sesión de pago',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => $session,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al crear sesión de pago', $e, [
                'invoice_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la sesión de pago',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, int $id)
    {
        try {
            $professional = $request->user()->professional;
            $sessionId = $request->query('session_id');

            if (!$sessionId) {
                return redirect()->route('psychologist.invoices.show', $id)
                    ->with('error', 'Sesión de pago no válida');
            }

            // Verify ownership
            if (!$this->invoiceService->verifyOwnership($id, $professional)) {
                return redirect()->route('psychologist.invoices.index')
                    ->with('error', 'Factura no encontrada');
            }

            $paymentService = app(StripePaymentService::class);
            $invoice = $paymentService->verifyPayment($sessionId);

            if (!$invoice || $invoice->id != $id) {
                return redirect()->route('psychologist.invoices.show', $id)
                    ->with('error', 'No se pudo verificar el pago. Por favor, contacta con soporte.');
            }

            return redirect()->route('psychologist.invoices.show', $id)
                ->with('success', '¡Pago realizado exitosamente!');
        } catch (\Exception $e) {
            $this->logError('Error al procesar pago exitoso', $e, [
                'invoice_id' => $id,
            ]);

            return redirect()->route('psychologist.invoices.show', $id)
                ->with('error', 'Error al procesar el pago. Por favor, contacta con soporte.');
        }
    }
}
