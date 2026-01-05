<?php

namespace App\Modules\Psychology\ClinicalNotes\Controllers;

use App\Modules\Psychology\ClinicalNotes\Services\ClinicalNoteService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClinicalNoteController extends Controller
{
    public function __construct(
        private ClinicalNoteService $service
    ) {}

    /**
     * List clinical notes
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'contact_id' => $request->contact_id,
            'high_risk' => $request->boolean('high_risk'),
            'per_page' => $request->get('per_page', 20),
        ];

        $notes = $this->service->getNotesForProfessional(
            $request->user()->professional->id,
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $notes,
        ]);
    }

    /**
     * Create clinical note
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'session_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'interventions_used' => 'nullable|array',
            'homework' => 'nullable|string',
            'risk_assessment' => 'nullable|in:sin_riesgo,riesgo_bajo,riesgo_moderado,riesgo_alto,riesgo_inminente',
            'risk_details' => 'nullable|string',
            'progress_rating' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            $note = $this->service->createNote(
                $validated,
                $request->user()->professional->id,
                $request->user()->id
            );

            $this->logUserAction('Nota clínica creada', [
                'note_id' => $note->id,
                'contact_id' => $note->contact_id,
                'session_number' => $note->session_number,
                'risk_assessment' => $note->risk_assessment,
            ]);

            // Log high risk assessments
            if (in_array($note->risk_assessment, ['riesgo_alto', 'riesgo_inminente'])) {
                $this->logWarning('Nota clínica con riesgo alto detectado', [
                    'note_id' => $note->id,
                    'contact_id' => $note->contact_id,
                    'risk_assessment' => $note->risk_assessment,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Nota clínica creada exitosamente',
                'data' => $note->load('contact'),
            ], 201);
        } catch (\Exception $e) {
            $this->logError('Error al crear nota clínica', $e, [
                'contact_id' => $validated['contact_id'],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la nota clínica',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Show clinical note
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $repository = app(\App\Modules\Psychology\ClinicalNotes\Repositories\ClinicalNoteRepository::class);
        
        try {
            $note = $repository->findForProfessional(
                $id,
                $request->user()->professional->id
            )->load(['contact', 'appointment']);

            return response()->json([
                'success' => true,
                'data' => $note,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nota clínica no encontrada',
            ], 404);
        }
    }

    /**
     * Update clinical note
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'progress_rating' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            $note = $this->service->updateNote(
                $id,
                $validated,
                $request->user()->professional->id,
                $request->user()->id
            );

            $this->logUserAction('Nota clínica actualizada', [
                'note_id' => $note->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nota clínica actualizada exitosamente',
                'data' => $note,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al actualizar nota clínica', $e, [
                'note_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], $e->getMessage() === 'No se puede editar una nota clínica firmada' ? 422 : 500);
        }
    }

    /**
     * Sign clinical note
     */
    public function sign(Request $request, int $id): JsonResponse
    {
        try {
            $note = $this->service->signNote(
                $id,
                $request->user()->professional->id
            );

            $this->logUserAction('Nota clínica firmada', [
                'note_id' => $note->id,
                'contact_id' => $note->contact_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nota clínica firmada exitosamente',
                'data' => $note,
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al firmar nota clínica', $e, [
                'note_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}

