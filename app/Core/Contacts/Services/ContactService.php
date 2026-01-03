<?php

namespace App\Core\Contacts\Services;

use App\Core\Contacts\Models\Contact;
use App\Core\Contacts\Repositories\ContactRepository;
use App\Core\Users\Models\Professional;
use App\Shared\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService implements ServiceInterface
{
    public function __construct(
        private ContactRepository $repository
    ) {}

    /**
     * Create a new contact for a professional
     */
    public function createForProfessional(Professional $professional, array $data, int $createdBy): Contact
    {
        $data['professional_id'] = $professional->id;
        $data['created_by'] = $createdBy;
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->repository->create($data);
    }

    /**
     * Update an existing contact
     */
    public function updateContact(string $id, array $data, int $updatedBy): Contact
    {
        $data['updated_by'] = $updatedBy;
        return $this->repository->update($id, $data);
    }

    /**
     * Get contacts for a professional with filters
     */
    public function getContactsForProfessional(
        Professional $professional,
        array $filters = [],
        bool $paginate = true,
        int $perPage = 20
    ): Collection|LengthAwarePaginator {
        $filters['professional_id'] = $professional->id;

        if ($paginate) {
            return $this->repository->paginate($filters, $perPage);
        }

        return $this->repository->findAll($filters);
    }

    /**
     * Get a single contact by ID
     */
    public function getContact(string $id, array $with = []): Contact
    {
        $contact = $this->repository->findOrFail($id);
        
        if (!empty($with)) {
            $contact->load($with);
        }

        return $contact;
    }

    /**
     * Archive a contact
     */
    public function archiveContact(string $id): Contact
    {
        return $this->repository->archive($id);
    }

    /**
     * Unarchive a contact
     */
    public function unarchiveContact(string $id): Contact
    {
        return $this->repository->unarchive($id);
    }

    /**
     * Delete a contact (soft delete)
     */
    public function deleteContact(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Search contacts
     */
    public function searchContacts(string $query, Professional $professional): Collection
    {
        return $this->repository->search($query, $professional->id);
    }

    /**
     * Verify contact belongs to professional
     */
    public function verifyOwnership(string $contactId, Professional $professional): bool
    {
        $contact = $this->repository->find($contactId);
        
        if (!$contact) {
            return false;
        }

        return $contact->professional_id === $professional->id;
    }
}

