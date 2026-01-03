<?php

namespace App\Livewire\Appointments;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AppointmentList extends Component
{
    public bool $showCreateModal = false;
    public array $patients = [];
    
    // Form fields
    public string $contact_id = '';
    public string $appointment_date = '';
    public string $appointment_time = '';
    public string $duration = '60';
    public string $type = 'in_person';
    public string $notes = '';

    protected $rules = [
        'contact_id' => 'required',
        'appointment_date' => 'required|date',
        'appointment_time' => 'required',
        'duration' => 'required|integer|min:15',
        'type' => 'required|in:in_person,video_call',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadPatients();
    }

    private function loadPatients()
    {
        try {
            $response = Http::withToken(session('api_token'))
                ->get(url('/api/v1/contacts'), ['active' => 1]);

            if ($response->successful()) {
                $this->patients = $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            // Handle error
        }
    }

    public function getAppointments()
    {
        try {
            $response = Http::withToken(session('api_token'))
                ->get(url('/api/v1/appointments'), [
                    'upcoming' => 1,
                ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            // Handle error
        }
        
        return [];
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function createAppointment()
    {
        $this->validate();

        try {
            $datetime = $this->appointment_date . ' ' . $this->appointment_time;
            
            $response = Http::withToken(session('api_token'))
                ->post(url('/api/v1/appointments'), [
                    'contact_id' => $this->contact_id,
                    'appointment_datetime' => $datetime,
                    'duration_minutes' => (int) $this->duration,
                    'appointment_type' => $this->type,
                    'notes' => $this->notes,
                ]);

            if ($response->successful()) {
                $this->closeCreateModal();
                session()->flash('success', 'Cita creada exitosamente');
            } else {
                $data = $response->json();
                session()->flash('error', $data['message'] ?? 'Error al crear cita');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error de conexión');
        }
    }

    private function resetForm()
    {
        $this->contact_id = '';
        $this->appointment_date = '';
        $this->appointment_time = '';
        $this->duration = '60';
        $this->type = 'in_person';
        $this->notes = '';
    }

    public function render()
    {
        return view('livewire.appointments.appointment-list', [
            'appointments' => $this->getAppointments(),
        ]);
    }
}
