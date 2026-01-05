<?php

namespace App\Core\ConsentForms\Repositories;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ConsentFormRepository implements RepositoryInterface
{
    public function __construct(
        private ConsentForm $model
    ) {}

    /**
     * Find a consent form by ID
     */
    public function find(string $id): ?ConsentForm
    {
        return $this->model->find($id);
    }

    /**
     * Find a consent form by ID or fail
     */
    public function findOrFail(string $id): ConsentForm
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get all consent forms with optional filters
     */
    public function findAll(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['professional_id'])) {
            $query->where('professional_id', $filters['professional_id']);
        }

        if (isset($filters['contact_id'])) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['consent_type'])) {
            $query->where('consent_type', $filters['consent_type']);
        }

        if (isset($filters['is_valid']) && $filters['is_valid'] !== null) {
            $query->where('is_valid', $filters['is_valid']);
        }

        if (isset($filters['signed']) && $filters['signed']) {
            $query->signed();
        }

        if (isset($filters['pending']) && $filters['pending']) {
            $query->pending();
        }

        if (isset($filters['revoked']) && $filters['revoked']) {
            $query->revoked();
        }

        if (isset($filters['valid']) && $filters['valid']) {
            $query->valid();
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->get();
    }

    /**
     * Get paginated consent forms
     */
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (isset($filters['professional_id'])) {
            $query->where('professional_id', $filters['professional_id']);
        }

        if (isset($filters['contact_id'])) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['consent_type'])) {
            $query->where('consent_type', $filters['consent_type']);
        }

        if (isset($filters['is_valid']) && $filters['is_valid'] !== null) {
            $query->where('is_valid', $filters['is_valid']);
        }

        if (isset($filters['signed']) && $filters['signed']) {
            $query->signed();
        }

        if (isset($filters['pending']) && $filters['pending']) {
            $query->pending();
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->with(['professional', 'contact'])->paginate($perPage);
    }

    /**
     * Create a new consent form
     */
    public function create(array $data): ConsentForm
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing consent form
     */
    public function update(string $id, array $data): ConsentForm
    {
        $consentForm = $this->findOrFail($id);
        $consentForm->update($data);
        return $consentForm->fresh();
    }

    /**
     * Delete a consent form (soft delete)
     */
    public function delete(string $id): bool
    {
        $consentForm = $this->findOrFail($id);
        return $consentForm->delete();
    }

    /**
     * Get consent forms for a specific contact
     */
    public function findByContact(int $contactId, array $filters = []): Collection
    {
        $filters['contact_id'] = $contactId;
        return $this->findAll($filters);
    }

    /**
     * Get consent forms for a specific professional
     */
    public function findByProfessional(int $professionalId, array $filters = []): Collection
    {
        $filters['professional_id'] = $professionalId;
        return $this->findAll($filters);
    }

    /**
     * Get valid consent forms for a contact of a specific type
     */
    public function findValidByContactAndType(int $contactId, string $consentType): ?ConsentForm
    {
        return $this->model
            ->where('contact_id', $contactId)
            ->where('consent_type', $consentType)
            ->valid()
            ->orderBy('signed_at', 'desc')
            ->first();
    }

    /**
     * Check if contact has valid consent of a specific type
     */
    public function hasValidConsent(int $contactId, string $consentType): bool
    {
        return $this->model
            ->where('contact_id', $contactId)
            ->where('consent_type', $consentType)
            ->valid()
            ->exists();
    }
}

