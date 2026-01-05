<?php

namespace App\Modules\Psychology\ClinicalNotes\Services;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Modules\Psychology\ClinicalNotes\Repositories\ClinicalNoteRepository;
use App\Core\Contacts\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Clinical Note Service
 * 
 * Business logic for psychology clinical notes (SOAP format)
 */
class ClinicalNoteService
{
    public function __construct(
        private ClinicalNoteRepository $repository
    ) {}

    /**
     * Get all clinical notes for a professional
     */
    public function getNotesForProfessional(int $professionalId, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->findByProfessional($professionalId, $filters);
    }

    /**
     * Get clinical notes for a specific contact
     */
    public function getNotesForContact(int $contactId, int $professionalId): Collection
    {
        return $this->repository->findByContact($contactId, $professionalId);
    }

    /**
     * Create a new clinical note
     */
    public function createNote(array $data, int $professionalId, int $userId): ClinicalNote
    {
        // Auto-increment session number
        if (!isset($data['session_number'])) {
            $data['session_number'] = ClinicalNote::getNextSessionNumber($data['contact_id']);
        }

        $data['professional_id'] = $professionalId;
        $data['created_by'] = $userId;

        return $this->repository->create($data);
    }

    /**
     * Update a clinical note
     */
    public function updateNote(int $noteId, array $data, int $professionalId, int $userId): ClinicalNote
    {
        $note = $this->repository->findForProfessional($noteId, $professionalId);

        if ($note->isSigned()) {
            throw new \Exception('No se puede editar una nota clínica firmada');
        }

        $data['updated_by'] = $userId;

        return $this->repository->update($noteId, $data);
    }

    /**
     * Sign a clinical note
     */
    public function signNote(int $noteId, int $professionalId): ClinicalNote
    {
        $note = $this->repository->findForProfessional($noteId, $professionalId);
        $note->sign();

        return $note->fresh();
    }

    /**
     * Get high-risk clinical notes
     */
    public function getHighRiskNotes(int $professionalId): Collection
    {
        return $this->repository->findHighRisk($professionalId);
    }
}

