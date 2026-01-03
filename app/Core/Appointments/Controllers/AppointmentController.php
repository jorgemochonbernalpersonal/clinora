<?php

namespace App\Core\Appointments\Controllers;

use App\Core\Appointments\Requests\StoreAppointmentRequest;
use App\Core\Appointments\Requests\UpdateAppointmentRequest;
use App\Core\Appointments\Resources\AppointmentResource;
use App\Core\Appointments\Services\AppointmentService;
use App\Core\Contacts\Models\Contact;
use App\Shared\Exceptions\AppointmentConflictException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentService $appointmentService
    ) {}

    /**
     * List appointments
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            $filters = [
                'status' => $request->input('status'),
                'type' => $request->input('type'),
                'upcoming' => $request->boolean('upcoming'),
                'today' => $request->boolean('today'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'order_by' => $request->input('order_by', 'start_time'),
                'order_dir' => $request->input('order_dir', 'asc'),
            ];

            $appointments = $this->appointmentService->getAppointmentsForProfessional(
                $professional,
                array_filter($filters),
                true,
                $request->input('per_page', 50)
            );

            return response()->json([
                'success' => true,
                'data' => AppointmentResource::collection($appointments->items()),
                'meta' => [
                    'current_page' => $appointments->currentPage(),
                    'last_page' => $appointments->lastPage(),
                    'per_page' => $appointments->perPage(),
                    'total' => $appointments->total(),
                ],
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al listar citas', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las citas',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Create appointment
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {
            $professional = $request->user()->professional;
            $contact = Contact::findOrFail($request->validated()['contact_id']);

            // Verify contact belongs to professional
            if ($contact->professional_id !== $professional->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El paciente no pertenece a tu práctica',
                ], 403);
            }

            $appointment = $this->appointmentService->createAppointment(
                $professional,
                $contact,
                $request->validated(),
                $request->user()->id
            );

            $this->logUserAction('Cita creada', [
                'appointment_id' => $appointment->id,
                'contact_id' => $appointment->contact_id,
                'start_time' => $appointment->start_time->toIso8601String(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita creada exitosamente',
                'data' => new AppointmentResource($appointment->load('contact')),
            ], 201);
        } catch (AppointmentConflictException $e) {
            $this->logWarning('Intento de crear cita con conflicto de horario', [
                'contact_id' => $request->input('contact_id'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 409);
        } catch (\Exception $e) {
            $this->logError('Error al crear cita', $e, [
                'contact_id' => $request->input('contact_id'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cita',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Show appointment
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->appointmentService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada',
                ], 404);
            }

            $appointment = $this->appointmentService->getAppointment($id, [
                'contact',
                'clinicalNote',
            ]);

            return response()->json([
                'success' => true,
                'data' => new AppointmentResource($appointment),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al obtener cita', $e, [
                'appointment_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la cita',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Update appointment
     */
    public function update(UpdateAppointmentRequest $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->appointmentService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada',
                ], 404);
            }

            $appointment = $this->appointmentService->updateAppointment(
                $id,
                $request->validated(),
                $request->user()->id
            );

            $this->logUserAction('Cita actualizada', [
                'appointment_id' => $appointment->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => new AppointmentResource($appointment->load('contact')),
            ]);
        } catch (AppointmentConflictException $e) {
            $this->logWarning('Intento de actualizar cita con conflicto de horario', [
                'appointment_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 409);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada',
            ], 404);
        } catch (\Exception $e) {
            $this->logError('Error al actualizar cita', $e, [
                'appointment_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cita',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Cancel appointment
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $professional = $request->user()->professional;

            // Verify ownership
            if (!$this->appointmentService->verifyOwnership($id, $professional)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada',
                ], 404);
            }

            $appointment = $this->appointmentService->cancelAppointment(
                $id,
                $request->input('reason')
            );

            $this->logUserAction('Cita cancelada', [
                'appointment_id' => $appointment->id,
                'reason' => $request->input('reason'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada exitosamente',
            ]);
        } catch (\Exception $e) {
            $this->logWarning('Error al cancelar cita', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
