<?php

namespace App\Livewire\Dashboard\Appointments;

use App\Core\Appointments\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Calendar extends Component
{
    public function getEventsProperty(): Collection
    {
        return Appointment::query()
            ->where('professional_id', auth()->user()->professional->id)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->contact->full_name ?? 'Cita',
                    'start' => $appointment->start_time->toIso8601String(),
                    'end' => $appointment->end_time->toIso8601String(),
                    'backgroundColor' => $this->getStatusColor($appointment->status),
                    'borderColor' => $this->getStatusColor($appointment->status),
                    'extendedProps' => [
                        'patient' => $appointment->contact->full_name ?? 'Sin nombre',
                        'type' => $appointment->type->label(),
                        'status' => $appointment->status->label(),
                    ]
                ];
            });
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
        }
    }

    public function render()
    {
        return view('livewire.dashboard.appointments.calendar', [
            'events' => $this->events
        ]);
    }
}
