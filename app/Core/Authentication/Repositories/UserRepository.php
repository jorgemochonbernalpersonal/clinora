<?php

namespace App\Core\Authentication\Repositories;

use App\Models\User;
use App\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Repository
 *
 * Data access layer for User model
 * Handles all database operations related to users
 */
class UserRepository implements RepositoryInterface
{
    public function __construct(
        private readonly User $model
    ) {}

    public function find(string $id): ?User
    {
        return $this->model->find($id);
    }

    public function findOrFail(string $id): User
    {
        return $this->model->findOrFail($id);
    }

    public function findAll(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['email_verified']) && $filters['email_verified'] !== null) {
            if ($filters['email_verified']) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        if (isset($filters['with'])) {
            $query->with($filters['with']);
        }

        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->get();
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (isset($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['email_verified']) && $filters['email_verified'] !== null) {
            if ($filters['email_verified']) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        if (isset($filters['with'])) {
            $query->with($filters['with']);
        }

        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->paginate($perPage);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): User
    {
        $user = $this->findOrFail($id);
        $user->update($data);
        return $user->fresh();
    }

    public function delete(string $id): bool
    {
        $user = $this->findOrFail($id);
        return $user->delete();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByEmailWith(string $email, array $relations = ['professional', 'roles']): ?User
    {
        return $this
            ->model
            ->where('email', $email)
            ->with($relations)
            ->first();
    }

    public function emailExists(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    public function updateLastLogin(User $user, string $ip): bool
    {
        return $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    public function findByUserType(string $userType, array $filters = []): Collection
    {
        $filters['user_type'] = $userType;
        return $this->findAll($filters);
    }

    public function findActive(array $filters = []): Collection
    {
        $filters['is_active'] = true;
        return $this->findAll($filters);
    }

    public function findVerified(array $filters = []): Collection
    {
        $filters['email_verified'] = true;
        return $this->findAll($filters);
    }

    public function search(string $query, array $filters = []): Collection
    {
        $baseQuery = $this->model->newQuery();

        // Apply base filters first
        if (isset($filters['user_type'])) {
            $baseQuery->where('user_type', $filters['user_type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $baseQuery->where('is_active', $filters['is_active']);
        }

        $baseQuery->where(function ($q) use ($query) {
            $q
                ->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        });

        if (isset($filters['with'])) {
            $baseQuery->with($filters['with']);
        }

        return $baseQuery->get();
    }

    public function findWithProfessional(string $id): ?User
    {
        return $this->model->with('professional')->find($id);
    }

    public function countByType(string $userType): int
    {
        return $this->model->where('user_type', $userType)->count();
    }

    public function findByDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate, array $filters = []): Collection
    {
        $query = $this->model->whereBetween('created_at', [$startDate, $endDate]);

        if (isset($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        return $query->get();
    }
}
