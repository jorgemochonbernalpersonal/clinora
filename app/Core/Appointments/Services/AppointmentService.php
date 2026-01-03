<?php

namespace App\Core\Appointments\Services;

use App\Core\Appointments\Models\Appointment;
use App\Core\Appointments\Repositories\AppointmentRepository;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Exceptions\AppointmentConflictException;
use App\Shared\Interfaces\ServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentService implements ServiceInterface
{
    public function __construct(
        private AppointmentRepository $repository
    ) {}

    /**
     * Create a new appointment
     * 
     * @throws AppointmentConflictException
     */
    public function createAppointment(
        Professional $professional,
        Contact $contact,
        array $data,
        int $createdBy
    ): Appointment {
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        // Check for conflicts
        $conflicts = $this->repository->findConflicts(
            $professional->id,
            $startTime,
            $endTime
        );

        if ($conflicts->isNotEmpty()) {
            throw new AppointmentConflictException(
                'Ya existe una cita programada en este horario'
            );
        }

        $appointmentData = [
            'professional_id' => $professional->id,
            'contact_id' => $contact->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => $data['type'],
            'status' => AppointmentStatus::SCHEDULED,
            'title' => $data['title'] ?? null,
            'notes' => $data['notes'] ?? null,
            'internal_notes' => $data['internal_notes'] ?? null,
            'price' => $data['price'] ?? null,
            'currency' => $data['currency'] ?? 'EUR',
            'created_by' => $createdBy,
        ];

        return $this->repository->create($appointmentData);
    }

    /**
     * Update an existing appointment
     * 
     * @throws AppointmentConflictException
     */
    public function updateAppointment(
        string $id,
        array $data,
        int $updatedBy
    ): Appointment {
        $appointment = $this->repository->findOrFail($id);

        // If time is being updated, check for conflicts
        if (isset($data['start_time']) || isset($data['end_time'])) {
            $startTime = isset($data['start_time']) 
                ? Carbon::parse($data['start_time']) 
                : $appointment->start_time;
            $endTime = isset($data['end_time']) 
                ? Carbon::parse($data['end_time']) 
                : $appointment->end_time;

            $conflicts = $this->repository->findConflicts(
                $appointment->professional_id,
                $startTime,
                $endTime,
                $appointment->id // Exclude current appointment
            );

            if ($conflicts->isNotEmpty()) {
                throw new AppointmentConflictException(
                    'Ya existe otra cita programada en este horario'
                );
            }
        }

        $data['updated_by'] = $updatedBy;
        return $this->repository->update($id, $data);
    }

    /**
     * Get appointments for a professional
     */
    public function getAppointmentsForProfessional(
        Professional $professional,
        array $filters = [],
        bool $paginate = true,
        int $perPage = 50
    ): Collection|LengthAwarePaginator {
        $filters['professional_id'] = $professional->id;

        if ($paginate) {
            return $this->repository->paginate($filters, $perPage);
        }

        return $this->repository->findAll($filters);
    }

    /**
     * Get a single appointment by ID
     */
    public function getAppointment(string $id, array $with = []): Appointment
    {
        $appointment = $this->repository->findOrFail($id);
        
        if (!empty($with)) {
            $appointment->load($with);
        }

        return $appointment;
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment(string $id, ?string $reason = null): Appointment
    {
        $appointment = $this->repository->findOrFail($id);

        if (!$appointment->canBeCancelled()) {
            throw new \Exception('Esta cita no puede ser cancelada');
        }

        $appointment->cancel($reason);
        return $appointment->fresh();
    }

    /**
     * Mark appointment as completed
     */
    public function completeAppointment(string $id): Appointment
    {
        $appointment = $this->repository->findOrFail($id);
        $appointment->complete();
        return $appointment->fresh();
    }

    /**
     * Verify appointment belongs to professional
     */
    public function verifyOwnership(string $appointmentId, Professional $professional): bool
    {
        $appointment = $this->repository->find($appointmentId);
        
        if (!$appointment) {
            return false;
        }

        return $appointment->professional_id === $professional->id;
    }

    /**
     * Get upcoming appointments for a professional
     */
    public function getUpcomingAppointments(Professional $professional, int $limit = 10): Collection
    {
        return $this->repository->getUpcomingForProfessional($professional->id, $limit);
    }

    /**
     * Get today's appointments for a professional
     */
    public function getTodayAppointments(Professional $professional): Collection
    {
        return $this->repository->getTodayForProfessional($professional->id);
    }
}

