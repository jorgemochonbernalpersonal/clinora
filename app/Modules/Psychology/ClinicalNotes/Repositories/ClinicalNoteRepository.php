<?php

namespace App\Modules\Psychology\ClinicalNotes\Repositories;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Clinical Note Repository
 * 
 * Data access layer for psychology clinical notes
 */
class ClinicalNoteRepository implements RepositoryInterface
{
    public function find(string $id): ?ClinicalNote
    {
        return ClinicalNote::find($id);
    }

    public function findAll(array $filters = []): Collection
    {
        $query = ClinicalNote::query();

        if (isset($filters['professional_id'])) {
            $query->where('professional_id', $filters['professional_id']);
        }

        if (isset($filters['contact_id'])) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['high_risk'])) {
            $query->highRisk();
        }

        return $query->orderBy('session_date', 'desc')->get();
    }

    public function create(array $data): ClinicalNote
    {
        return ClinicalNote::create($data);
    }

    public function update(string $id, array $data): ClinicalNote
    {
        $note = $this->findOrFail($id);
        $note->update($data);

        return $note->fresh();
    }

    public function delete(string $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Find notes for a specific professional
     */
    public function findByProfessional(int $professionalId, array $filters = []): LengthAwarePaginator
    {
        $query = ClinicalNote::where('professional_id', $professionalId)
            ->with(['contact', 'appointment']);

        if (isset($filters['contact_id'])) {
            $query->byContact($filters['contact_id']);
        }

        if (isset($filters['high_risk'])) {
            $query->highRisk();
        }

        if (isset($filters['signed'])) {
            if ($filters['signed']) {
                $query->signed();
            } else {
                $query->where('is_signed', false);
            }
        }

        return $query->orderBy('session_date', 'desc')
            ->paginate($filters['per_page'] ?? 20);
    }

    /**
     * Find notes for a specific contact
     */
    public function findByContact(int $contactId, int $professionalId): Collection
    {
        return ClinicalNote::where('contact_id', $contactId)
            ->where('professional_id', $professionalId)
            ->byContact($contactId)
            ->with(['appointment'])
            ->get();
    }

    /**
     * Find a note for a specific professional (with authorization check)
     */
    public function findForProfessional(int $noteId, int $professionalId): ClinicalNote
    {
        return ClinicalNote::where('professional_id', $professionalId)
            ->findOrFail($noteId);
    }

    /**
     * Find high-risk notes
     */
    public function findHighRisk(int $professionalId): Collection
    {
        return ClinicalNote::where('professional_id', $professionalId)
            ->highRisk()
            ->with(['contact'])
            ->orderBy('session_date', 'desc')
            ->get();
    }

    /**
     * Find or fail
     */
    private function findOrFail(string $id): ClinicalNote
    {
        return ClinicalNote::findOrFail($id);
    }
}

