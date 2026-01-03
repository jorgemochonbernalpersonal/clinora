<?php

namespace App\Core\Appointments\Repositories;

use App\Core\Appointments\Models\Appointment;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class AppointmentRepository implements RepositoryInterface
{
    public function __construct(
        private Appointment $model
    ) {}

    /**
     * Find an appointment by ID
     */
    public function find(string $id): ?Appointment
    {
        return $this->model->find($id);
    }

    /**
     * Find an appointment by ID or fail
     */
    public function findOrFail(string $id): Appointment
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get all appointments with optional filters
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

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['upcoming']) && $filters['upcoming']) {
            $query->upcoming();
        }

        if (isset($filters['today']) && $filters['today']) {
            $query->today();
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('start_time', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('start_time', '<=', $filters['end_date']);
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'start_time';
        $orderDir = $filters['order_dir'] ?? 'asc';
        $query->orderBy($orderBy, $orderDir);

        return $query->get();
    }

    /**
     * Get paginated appointments
     */
    public function paginate(array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (isset($filters['professional_id'])) {
            $query->where('professional_id', $filters['professional_id']);
        }

        if (isset($filters['contact_id'])) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['upcoming']) && $filters['upcoming']) {
            $query->upcoming();
        }

        if (isset($filters['today']) && $filters['today']) {
            $query->today();
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('start_time', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('start_time', '<=', $filters['end_date']);
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'start_time';
        $orderDir = $filters['order_dir'] ?? 'asc';
        $query->orderBy($orderBy, $orderDir);

        return $query->paginate($perPage);
    }

    /**
     * Create a new appointment
     */
    public function create(array $data): Appointment
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing appointment
     */
    public function update(string $id, array $data): Appointment
    {
        $appointment = $this->findOrFail($id);
        $appointment->update($data);
        return $appointment->fresh();
    }

    /**
     * Delete an appointment (soft delete)
     */
    public function delete(string $id): bool
    {
        $appointment = $this->findOrFail($id);
        return $appointment->delete();
    }

    /**
     * Check for appointment conflicts
     * 
     * @param int $professionalId
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @param int|null $excludeAppointmentId Exclude this appointment ID (for updates)
     * @return Collection Conflicting appointments
     */
    public function findConflicts(
        int $professionalId,
        Carbon $startTime,
        Carbon $endTime,
        ?int $excludeAppointmentId = null
    ): Collection {
        $query = $this->model->where('professional_id', $professionalId)
            ->whereNotIn('status', [
                AppointmentStatus::CANCELLED->value,
                AppointmentStatus::NO_SHOW->value,
            ])
            ->where(function ($q) use ($startTime, $endTime) {
                // Check if existing appointment overlaps with new time range
                $q->where(function ($subQ) use ($startTime, $endTime) {
                    // Existing appointment starts within new range
                    $subQ->whereBetween('start_time', [$startTime, $endTime])
                        // Or existing appointment ends within new range
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        // Or existing appointment completely contains new range
                        ->orWhere(function ($containQ) use ($startTime, $endTime) {
                            $containQ->where('start_time', '<=', $startTime)
                                    ->where('end_time', '>=', $endTime);
                        });
                });
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->get();
    }

    /**
     * Get appointments for a specific professional
     */
    public function findByProfessional(int $professionalId, array $filters = []): Collection
    {
        $filters['professional_id'] = $professionalId;
        return $this->findAll($filters);
    }

    /**
     * Get appointments for a specific contact
     */
    public function findByContact(int $contactId, array $filters = []): Collection
    {
        $filters['contact_id'] = $contactId;
        return $this->findAll($filters);
    }

    /**
     * Get upcoming appointments for a professional
     */
    public function getUpcomingForProfessional(int $professionalId, int $limit = 10): Collection
    {
        return $this->model->where('professional_id', $professionalId)
            ->upcoming()
            ->limit($limit)
            ->get();
    }

    /**
     * Get today's appointments for a professional
     */
    public function getTodayForProfessional(int $professionalId): Collection
    {
        return $this->model->where('professional_id', $professionalId)
            ->today()
            ->get();
    }
}

