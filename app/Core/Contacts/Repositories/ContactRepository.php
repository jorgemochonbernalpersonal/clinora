<?php

namespace App\Core\Contacts\Repositories;

use App\Core\Contacts\Models\Contact;
use App\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements RepositoryInterface
{
    public function __construct(
        private Contact $model
    ) {}

    /**
     * Find a contact by ID
     */
    public function find(string $id): ?Contact
    {
        return $this->model->find($id);
    }

    /**
     * Find a contact by ID or fail
     */
    public function findOrFail(string $id): Contact
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get all contacts with optional filters
     */
    public function findAll(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['professional_id'])) {
            $query->where('professional_id', $filters['professional_id']);
        }

        if (isset($filters['search'])) {
            $query->searchByName($filters['search']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['tags']) && is_array($filters['tags'])) {
            foreach ($filters['tags'] as $tag) {
                $query->whereJsonContains('tags', $tag);
            }
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'last_name';
        $orderDir = $filters['order_dir'] ?? 'asc';
        $query->orderBy($orderBy, $orderDir);
        $query->orderBy('first_name', 'asc');

        return $query->get();
    }

    /**
     * Get paginated contacts
     */
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (isset($filters['professional_id'])) {
            $query->where('professional_id', $filters['professional_id']);
        }

        if (isset($filters['search'])) {
            $query->searchByName($filters['search']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'last_name';
        $orderDir = $filters['order_dir'] ?? 'asc';
        $query->orderBy($orderBy, $orderDir);
        $query->orderBy('first_name', 'asc');

        return $query->paginate($perPage);
    }

    /**
     * Create a new contact
     */
    public function create(array $data): Contact
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing contact
     */
    public function update(string $id, array $data): Contact
    {
        $contact = $this->findOrFail($id);
        $contact->update($data);
        return $contact->fresh();
    }

    /**
     * Delete a contact (soft delete)
     */
    public function delete(string $id): bool
    {
        $contact = $this->findOrFail($id);
        return $contact->delete();
    }

    /**
     * Archive a contact
     */
    public function archive(string $id): Contact
    {
        $contact = $this->findOrFail($id);
        $contact->archive();
        return $contact->fresh();
    }

    /**
     * Unarchive a contact
     */
    public function unarchive(string $id): Contact
    {
        $contact = $this->findOrFail($id);
        $contact->unarchive();
        return $contact->fresh();
    }

    /**
     * Get contacts for a specific professional
     */
    public function findByProfessional(int $professionalId, array $filters = []): Collection
    {
        $filters['professional_id'] = $professionalId;
        return $this->findAll($filters);
    }

    /**
     * Search contacts by name or email
     */
    public function search(string $query, int $professionalId): Collection
    {
        return $this->model->where('professional_id', $professionalId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->get();
    }
}

