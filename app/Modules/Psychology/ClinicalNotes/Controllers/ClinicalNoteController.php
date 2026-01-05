<?php

namespace App\Modules\Psychology\ClinicalNotes\Controllers;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClinicalNoteController extends Controller
{
    /**
     * List clinical notes
     */
    public function index(Request $request): JsonResponse
    {
        $notes = ClinicalNote::where('professional_id', $request->user()->professional->id)
            ->with(['contact', 'appointment'])
            ->when($request->contact_id, fn($q, $contactId) => $q->byContact($contactId))
            ->when($request->high_risk, fn($q) => $q->highRisk())
            ->orderBy('session_date', 'desc')
            ->paginate(20);

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

        // Auto-increment session number
        $sessionNumber = ClinicalNote::getNextSessionNumber($validated['contact_id']);

        try {
            $note = ClinicalNote::create(array_merge($validated, [
                'professional_id' => $request->user()->professional->id,
                'session_number' => $sessionNumber,
                'created_by' => $request->user()->id,
            ]));

            $this->logUserAction('Nota clínica creada', [
                'note_id' => $note->id,
                'contact_id' => $note->contact_id,
                'session_number' => $sessionNumber,
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
        $note = ClinicalNote::where('professional_id', $request->user()->professional->id)
            ->with(['contact', 'appointment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $note,
        ]);
    }

    /**
     * Update clinical note
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $note = ClinicalNote::where('professional_id', $request->user()->professional->id)
            ->findOrFail($id);

        if ($note->isSigned()) {
            $this->logWarning('Intento de editar nota clínica firmada', [
                'note_id' => $note->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No se puede editar una nota firmada',
            ], 422);
        }

        $validated = $request->validate([
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'progress_rating' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            $note->update(array_merge($validated, [
                'updated_by' => $request->user()->id,
            ]));

            $this->logUserAction('Nota clínica actualizada', [
                'note_id' => $note->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nota clínica actualizada exitosamente',
                'data' => $note->fresh(),
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al actualizar nota clínica', $e, [
                'note_id' => $note->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la nota clínica',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    /**
     * Sign clinical note
     */
    public function sign(Request $request, int $id): JsonResponse
    {
        $note = ClinicalNote::where('professional_id', $request->user()->professional->id)
            ->findOrFail($id);

        try {
            $note->sign();

            $this->logUserAction('Nota clínica firmada', [
                'note_id' => $note->id,
                'contact_id' => $note->contact_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nota clínica firmada exitosamente',
                'data' => $note->fresh(),
            ]);
        } catch (\Exception $e) {
            $this->logError('Error al firmar nota clínica', $e, [
                'note_id' => $note->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}

