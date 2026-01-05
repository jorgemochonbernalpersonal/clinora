<?php

namespace App\Livewire\Psychologist\Appointments;

use App\Core\Appointments\Models\Appointment;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Collection;
use Carbon\Carbon;

#[Layout('layouts.dashboard')]
class Calendar extends Component
{
    public $searchTerm = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $selectedAppointmentId = null;
    public $isLoading = false;

    public function updatedSearchTerm()
    {
        $this->dispatch('refresh-calendar', events: $this->events->toArray());
    }

    public function updatedStatusFilter()
    {
        $this->dispatch('refresh-calendar', events: $this->events->toArray());
    }

    public function updatedTypeFilter()
    {
        $this->dispatch('refresh-calendar', events: $this->events->toArray());
    }

    public function getEventsProperty(): Collection
    {
        $query = Appointment::query()
            ->where('professional_id', auth()->user()->professional->id)
            ->with('contact');

        // Search filter
        if ($this->searchTerm) {
            $query->whereHas('contact', function($q) {
                $q->where('first_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Type filter
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        return $query->get()->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->contact->full_name ?? 'Cita',
                'start' => $appointment->start_time->toIso8601String(),
                'end' => $appointment->end_time->toIso8601String(),
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor' => $this->getStatusColor($appointment->status),
                'extendedProps' => [
                    'patient' => $appointment->contact->full_name ?? 'Sin nombre',
                    'patient_id' => $appointment->contact_id,
                    'type' => $appointment->type->label(),
                    'type_value' => $appointment->type->value,
                    'status' => $appointment->status->label(),
                    'status_value' => $appointment->status->value,
                    'notes' => $appointment->notes,
                ]
            ];
        });
    }

    public function getStatsProperty(): array
    {
        $professional_id = auth()->user()->professional->id;
        $now = Carbon::now();

        return [
            'today' => Appointment::where('professional_id', $professional_id)
                ->whereDate('start_time', $now->toDateString())
                ->count(),
            'thisWeek' => Appointment::where('professional_id', $professional_id)
                ->whereBetween('start_time', [$now->startOfWeek(), $now->copy()->endOfWeek()])
                ->count(),
            'completed' => Appointment::where('professional_id', $professional_id)
                ->whereMonth('start_time', $now->month)
                ->where('status', 'completed')
                ->count(),
            'total' => Appointment::where('professional_id', $professional_id)
                ->whereMonth('start_time', $now->month)
                ->count(),
        ];
    }

    private function getStatusColor($status): string
    {
        return match($status->value) {
            'confirmed' => '#10B981', // green-500
            'scheduled' => '#3B82F6', // blue-500
            'completed' => '#6B7280', // gray-500
            'cancelled' => '#EF4444', // red-500
            'no_show' => '#F59E0B',   // amber-500
            default => '#3B82F6',
        };
    }

    public function updateAppointmentOrder($id, $start, $end)
    {
        $appointment = Appointment::find($id);
        
        if ($appointment && $appointment->professional_id === auth()->user()->professional->id) {
            $appointment->update([
                'start_time' => Carbon::parse($start),
                'end_time' => Carbon::parse($end),
            ]);

            $this->dispatch('show-toast', type: 'success', message: 'Cita reprogramada correctamente');
            $this->dispatch('refresh-calendar', events: $this->events->toArray());
        }
    }

    public function updateStatus($appointmentId, $newStatus)
    {
        $appointment = Appointment::find($appointmentId);
        
        if ($appointment && $appointment->professional_id === auth()->user()->professional->id) {
            $appointment->update(['status' => $newStatus]);
            $this->dispatch('show-toast', type: 'success', message: 'Estado actualizado');
            $this->dispatch('refresh-calendar', events: $this->events->toArray());
            $this->selectedAppointmentId = null;
        }
    }

    public function deleteAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        
        if ($appointment && $appointment->professional_id === auth()->user()->professional->id) {
            $appointment->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Cita eliminada');
            $this->dispatch('refresh-calendar', events: $this->events->toArray());
            $this->selectedAppointmentId = null;
        }
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->statusFilter = 'all';
        $this->typeFilter = 'all';
        $this->dispatch('refresh-calendar', events: $this->events->toArray());
    }

    public function render()
    {
        return view('livewire.psychologist.appointments.calendar', [
            'events' => $this->events,
            'stats' => $this->stats,
        ]);
    }
}
