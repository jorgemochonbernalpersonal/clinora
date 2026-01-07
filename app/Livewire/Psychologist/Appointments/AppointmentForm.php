<?php

namespace App\Livewire\Psychologist\Appointments;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Http\Request;

#[Layout('layouts.dashboard')]
class AppointmentForm extends Component
{
    public ?Appointment $appointment = null;
    
    // Form fields
    public $contact_id;
    public $start_time;
    public $end_time;
    public $type = 'in_person';
    public $status = 'scheduled';
    public $notes;
    
    // UI State
    public $isEditing = false;
    public $contacts = [];

    protected function rules()
    {
        return [
            'contact_id' => 'required|exists:contacts,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => ['required', Rule::enum(AppointmentType::class)],
            'status' => ['required', Rule::enum(AppointmentStatus::class)],
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function mount($id = null)
    {
        $this->contacts = Contact::where('professional_id', auth()->user()->professional->id)
            ->orderBy('first_name')
            ->get();
            
        if ($id) {
            $this->loadAppointment($id);
        } else {
            // Get dates from Query String if available
            $this->prepareForCreate(
                request()->query('start'),
                request()->query('end')
            );
        }
    }

    public function prepareForCreate($start = null, $end = null)
    {
        $this->reset(['appointment', 'isEditing', 'contact_id', 'notes']);
        $this->isEditing = false;
        
        $now = now();
        $start = $start ? Carbon::parse($start) : $now->setTime(9, 0);
        
        // If selection is "all day" (date string only), set clearer times
        if (is_string($start) && strlen($start) <= 10) {
             $start = Carbon::parse($start)->setTime(9, 0);
        }

        $this->start_time = $start->format('Y-m-d\TH:i');
        
        // Default duration 1 hour
        $end = $end ? Carbon::parse($end) : $start->copy()->addHour();
        $this->end_time = $end->format('Y-m-d\TH:i');
    }

    public function loadAppointment($id)
    {
        $appointment = Appointment::find($id);
        
        // Ensure we got a single model, not a collection
        if ($appointment instanceof \Illuminate\Database\Eloquent\Collection) {
            return redirect()->route(profession_prefix() . '.appointments.index');
        }

        if (!$appointment || $appointment->professional_id !== auth()->user()->professional->id) {
            return redirect()->route(profession_prefix() . '.appointments.index');
        }

        $this->appointment = $appointment;
        $this->isEditing = true;
        
        $this->contact_id = $appointment->contact_id;
        $this->start_time = $appointment->start_time->format('Y-m-d\TH:i');
        $this->end_time = $appointment->end_time->format('Y-m-d\TH:i');
        $this->type = $appointment->type->value;
        $this->status = $appointment->status->value;
        $this->notes = $appointment->notes;
    }
    
    public function save()
    {
        $validated = $this->validate();
        
        $data = [
            'professional_id' => auth()->user()->professional->id,
            'contact_id' => $validated['contact_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ];

        if ($this->isEditing) {
            $this->appointment->update($data);
            session()->flash('success', 'Cita actualizada correctamente.');
        } else {
            Appointment::create($data);
            session()->flash('success', 'Cita creada correctamente.');
        }
        
        return redirect()->route(profession_prefix() . '.appointments.index');
    }

    public function render()
    {
        return view('livewire.psychologist.appointments.appointment-form');
    }
}
