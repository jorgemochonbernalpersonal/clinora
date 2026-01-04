<?php

namespace App\Core\Contacts\Controllers;

use App\Core\Contacts\Requests\StoreContactRequest;
use App\Core\Contacts\Requests\UpdateContactRequest;
use App\Core\Contacts\Resources\ContactResource;
use App\Core\Contacts\Services\ContactService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService
    ) {}

    /**
     * List all contacts for authenticated professional
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            $filters = [
                'search' => $request->input('search'),
                'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : null,
                'order_by' => $request->input('order_by', 'last_name'),
                'order_dir' => $request->input('order_dir', 'asc'),
            ];

            $contacts = $this->contactService->getContactsForProfessional(
                $professional,
                array_filter($filters),
                true,
                $request->input('per_page', 20)
            );

            return response()->json([
                'success' => true,
                'data' => ContactResource::collection($contacts->items()),
                'meta' => [
                    'current_page' => $contacts->currentPage(),
                    'last_page' => $contacts->lastPage(),
                    'per_page' => $contacts->perPage(),
                    'total' => $contacts->total(),
                ],
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al listar pacientes', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los pacientes',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Create a new contact
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            
            // Check if professional can add a new patient
            $planLimits = app(\App\Core\Subscriptions\Services\PlanLimitsService::class);
            
            if (!$planLimits->canAddPatient($professional)) {
                $stats = $planLimits->getUsageStats($professional);
                
                return response()->json([
                    'success' => false,
                    'message' => '¡Has alcanzado el límite de pacientes de tu plan!',
                    'error' => sprintf(
                        'Tu plan %s permite hasta %d pacientes. Tienes %d pacientes activos.',
                        $professional->subscription_plan->label(),
                        $stats['patient_limit'],
                        $stats['total_patients']
                    ),
                    'upgrade_required' => true,
                    'current_plan' => $professional->subscription_plan->value,
                    'usage_stats' => $stats,
                ], 403);
            }
            
            $contact = $this->contactService->createForProfessional(
                $professional,
                $request->validated(),
                $request->user()->id
            );

            $this->logUserAction('Paciente creado', [
                'contact_id' => $contact->id,
                'name' => $contact->full_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente creado exitosamente',
                'data' => new ContactResource($contact),
            ], 201);
        } catch (\Exception $e) {
            $this->logError('Error al crear paciente', $e, [
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el paciente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Get single contact
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->contactService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado',
                ], 404);
            }

            $contact = $this->contactService->getContact($id, [
                'appointments' => fn($q) => $q->latest()->take(5),
                'clinicalNotes' => fn($q) => $q->latest()->take(5),
            ]);

            return response()->json([
                'success' => true,
                'data' => new ContactResource($contact),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al obtener paciente', $e, [
                'contact_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el paciente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Update contact
     */
    public function update(UpdateContactRequest $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->contactService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado',
                ], 404);
            }

            $contact = $this->contactService->updateContact(
                $id,
                $request->validated(),
                $request->user()->id
            );

            $this->logUserAction('Paciente actualizado', [
                'contact_id' => $contact->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente actualizado exitosamente',
                'data' => new ContactResource($contact),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al actualizar paciente', $e, [
                'contact_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el paciente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Archive contact (soft delete)
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->contactService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado',
                ], 404);
            }

            $contact = $this->contactService->archiveContact($id);

            $this->logUserAction('Paciente archivado', [
                'contact_id' => $contact->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente archivado exitosamente',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al archivar paciente', $e, [
                'contact_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al archivar el paciente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }
}
