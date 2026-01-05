<?php

namespace App\Core\ConsentForms\Controllers;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Repositories\ConsentFormRepository;
use App\Core\ConsentForms\Requests\StoreConsentFormRequest;
use App\Core\ConsentForms\Requests\UpdateConsentFormRequest;
use App\Core\ConsentForms\Resources\ConsentFormResource;
use App\Core\ConsentForms\Services\ConsentFormService;
use App\Core\Contacts\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsentFormController extends Controller
{
    public function __construct(
        private ConsentFormRepository $repository,
        private ConsentFormService $service
    ) {}

    /**
     * List consent forms
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            $filters = [
                'professional_id' => $professional->id,
                'contact_id' => $request->contact_id,
                'consent_type' => $request->consent_type,
                'signed' => $request->boolean('signed'),
                'pending' => $request->boolean('pending'),
                'valid' => $request->boolean('valid'),
            ];

            $consentForms = $this->repository->paginate($filters, $request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => ConsentFormResource::collection($consentForms),
                'meta' => [
                    'current_page' => $consentForms->currentPage(),
                    'last_page' => $consentForms->lastPage(),
                    'per_page' => $consentForms->perPage(),
                    'total' => $consentForms->total(),
                ],
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al listar consentimientos', $e);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los consentimientos',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Create a new consent form
     */
    public function store(StoreConsentFormRequest $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            $validated = $request->validated();
            
            // Ensure professional_id matches authenticated user
            $validated['professional_id'] = $professional->id;
            
            // Verify contact belongs to professional
            $contact = Contact::where('id', $validated['contact_id'])
                ->where('professional_id', $professional->id)
                ->firstOrFail();
            
            // Add contact to data for template generation
            $validated['contact'] = $contact;
            
            // Create consent form using service (which handles template generation)
            $consentForm = $this->service->create($validated, $request->user()->id);
            
            $this->logUserAction('Consentimiento creado', [
                'consent_form_id' => $consentForm->id,
                'contact_id' => $consentForm->contact_id,
                'consent_type' => $consentForm->consent_type,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consentimiento creado exitosamente',
                'data' => new ConsentFormResource($consentForm->load(['professional', 'contact'])),
            ], 201);
        } catch (\Exception $e) {
            $this->logError('Error al crear consentimiento', $e, [
                'contact_id' => $request->contact_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el consentimiento',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Show a specific consent form
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            $consentForm = $this->repository->findOrFail($id);
            
            // Verify ownership
            if ($consentForm->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => new ConsentFormResource($consentForm->load(['professional', 'contact'])),
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al obtener consentimiento', $e, [
                'consent_form_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el consentimiento',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Update a consent form (only if not signed)
     */
    public function update(UpdateConsentFormRequest $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            $consentForm = $this->repository->findOrFail($id);
            
            // Verify ownership
            if ($consentForm->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                ], 403);
            }

            // Cannot update if signed
            if ($consentForm->isSigned()) {
                $this->logWarning('Intento de editar consentimiento firmado', [
                    'consent_form_id' => $consentForm->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'No se puede editar un consentimiento firmado',
                ], 422);
            }

            $consentForm = $this->repository->update($id, array_merge(
                $request->validated(),
                ['updated_by' => $request->user()->id]
            ));

            $this->logUserAction('Consentimiento actualizado', [
                'consent_form_id' => $consentForm->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consentimiento actualizado exitosamente',
                'data' => new ConsentFormResource($consentForm->load(['professional', 'contact'])),
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al actualizar consentimiento', $e, [
                'consent_form_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el consentimiento',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Sign a consent form
     */
    public function sign(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            $consentForm = $this->repository->findOrFail($id);
            
            // Verify ownership
            if ($consentForm->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'signature_data' => 'nullable|string', // Base64 signature
                'ip_address' => 'nullable|ip',
                'device_info' => 'nullable|string',
            ]);

            $consentForm->sign(
                $validated['signature_data'] ?? null,
                $validated['ip_address'] ?? null,
                $validated['device_info'] ?? null
            );

            $this->logUserAction('Consentimiento firmado', [
                'consent_form_id' => $consentForm->id,
                'contact_id' => $consentForm->contact_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consentimiento firmado exitosamente',
                'data' => new ConsentFormResource($consentForm->fresh()->load(['professional', 'contact'])),
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al firmar consentimiento', $e, [
                'consent_form_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Revoke a consent form
     */
    public function revoke(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            $consentForm = $this->repository->findOrFail($id);
            
            // Verify ownership
            if ($consentForm->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'reason' => 'nullable|string|max:500',
            ]);

            $consentForm->revoke($validated['reason'] ?? null);

            $this->logUserAction('Consentimiento revocado', [
                'consent_form_id' => $consentForm->id,
                'reason' => $validated['reason'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consentimiento revocado exitosamente',
                'data' => new ConsentFormResource($consentForm->fresh()->load(['professional', 'contact'])),
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al revocar consentimiento', $e, [
                'consent_form_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get available consent types for the professional
     */
    public function availableTypes(Request $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            $availableTypes = $this->service->getAvailableTypes($professional);

            return response()->json([
                'success' => true,
                'data' => $availableTypes,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al obtener tipos de consentimiento', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los tipos de consentimiento',
            ], 500);
        }
    }

    /**
     * Check if contact has valid consent of a specific type
     */
    public function checkValidConsent(Request $request, int $contactId): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            // Verify contact belongs to professional
            $contact = Contact::where('id', $contactId)
                ->where('professional_id', $professional->id)
                ->firstOrFail();

            $consentType = $request->get('consent_type');
            
            if (!$consentType) {
                return response()->json([
                    'success' => false,
                    'message' => 'El tipo de consentimiento es requerido',
                ], 422);
            }

            $hasValid = $this->repository->hasValidConsent($contactId, $consentType);
            $consentForm = $hasValid 
                ? $this->repository->findValidByContactAndType($contactId, $consentType)
                : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'has_valid_consent' => $hasValid,
                    'consent_form' => $consentForm ? new ConsentFormResource($consentForm) : null,
                ],
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al verificar consentimiento', $e, [
                'contact_id' => $contactId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el consentimiento',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Log warning
     */
    protected function logWarning(string $message, array $context = []): void
    {
        $this->log('warning', $message, array_merge([
            'user_id' => auth()->id(),
        ], $context));
    }

    /**
     * Internal logging helper to ensure consistency
     */
    private function log(string $level, string $message, array $context = []): void
    {
        Log::log($level, $message, $context);
    }
}

