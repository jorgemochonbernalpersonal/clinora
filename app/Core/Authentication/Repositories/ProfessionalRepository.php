<?php

namespace App\Core\Authentication\Repositories;

use App\Core\Users\Models\Professional;
use App\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Professional Repository
 * 
 * Data access layer for Professional model
 * Handles all database operations related to professionals
 */
class ProfessionalRepository implements RepositoryInterface
{
    public function __construct(
        private readonly Professional $model
    ) {}

    /**
     * Find professional by ID
     */
    public function find(string $id): ?Professional
    {
        return $this->model->find($id);
    }

    /**
     * Find professional by ID or fail
     */
    public function findOrFail(string $id): Professional
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get all professionals with optional filters
     */
    public function findAll(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['profession'])) {
            $query->where('profession', $filters['profession']);
        }

        if (isset($filters['profession_type'])) {
            $query->where('profession_type', $filters['profession_type']);
        }

        if (isset($filters['subscription_status'])) {
            $query->where('subscription_status', $filters['subscription_status']);
        }

        if (isset($filters['subscription_plan'])) {
            $query->where('subscription_plan', $filters['subscription_plan']);
        }

        if (isset($filters['is_early_adopter']) && $filters['is_early_adopter'] !== null) {
            $query->where('is_early_adopter', $filters['is_early_adopter']);
        }

        // Eager load relationships if needed
        if (isset($filters['with'])) {
            $query->with($filters['with']);
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->get();
    }

    /**
     * Get paginated professionals
     */
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (isset($filters['profession'])) {
            $query->where('profession', $filters['profession']);
        }

        if (isset($filters['profession_type'])) {
            $query->where('profession_type', $filters['profession_type']);
        }

        if (isset($filters['subscription_status'])) {
            $query->where('subscription_status', $filters['subscription_status']);
        }

        if (isset($filters['subscription_plan'])) {
            $query->where('subscription_plan', $filters['subscription_plan']);
        }

        if (isset($filters['is_early_adopter']) && $filters['is_early_adopter'] !== null) {
            $query->where('is_early_adopter', $filters['is_early_adopter']);
        }

        // Eager load relationships
        if (isset($filters['with'])) {
            $query->with($filters['with']);
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->paginate($perPage);
    }

    /**
     * Create a new professional
     */
    public function create(array $data): Professional
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing professional
     */
    public function update(string $id, array $data): Professional
    {
        $professional = $this->findOrFail($id);
        $professional->update($data);
        return $professional->fresh();
    }

    /**
     * Delete a professional (soft delete)
     */
    public function delete(string $id): bool
    {
        $professional = $this->findOrFail($id);
        return $professional->delete();
    }

    /**
     * Create professional profile for a user
     */
    public function createForUser(int $userId, array $data): Professional
    {
        return $this->create(array_merge($data, [
            'user_id' => $userId,
        ]));
    }

    /**
     * Find professional by user ID
     */
    public function findByUserId(int $userId): ?Professional
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Find professional by user ID with relationships
     */
    public function findByUserIdWith(int $userId, array $relations = ['user']): ?Professional
    {
        return $this->model->where('user_id', $userId)
            ->with($relations)
            ->first();
    }

    /**
     * Find professional by license number
     */
    public function findByLicenseNumber(string $licenseNumber): ?Professional
    {
        return $this->model->where('license_number', $licenseNumber)->first();
    }

    /**
     * Check if license number exists
     */
    public function licenseNumberExists(string $licenseNumber): bool
    {
        return $this->model->where('license_number', $licenseNumber)->exists();
    }

    /**
     * Find professionals by profession
     */
    public function findByProfession(string $profession, array $filters = []): Collection
    {
        $filters['profession'] = $profession;
        return $this->findAll($filters);
    }

    /**
     * Find professionals by profession type
     */
    public function findByProfessionType(string $professionType, array $filters = []): Collection
    {
        $filters['profession_type'] = $professionType;
        return $this->findAll($filters);
    }

    /**
     * Find active professionals (with active user)
     */
    public function findActive(array $filters = []): Collection
    {
        return $this->model->whereHas('user', function ($query) {
            $query->where('is_active', true);
        })->get();
    }

    /**
     * Find professionals with active subscription
     */
    public function findWithActiveSubscription(array $filters = []): Collection
    {
        $filters['subscription_status'] = 'active';
        return $this->findAll($filters);
    }

    /**
     * Find early adopters
     */
    public function findEarlyAdopters(array $filters = []): Collection
    {
        $filters['is_early_adopter'] = true;
        return $this->findAll($filters);
    }
}
