<?php

namespace App\Livewire\Dashboard\Patients;

use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
class PatientForm extends Component
{
    use WithFileUploads;

    public ?Contact $patient = null;
    
    // Form fields
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $date_of_birth;
    public $gender = 'other';
    public $address_street;
    public $address_city;
    public $address_postal_code;
    public $address_country = 'España';
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relationship;
    public $notes;
    
    // File Upload
    public $photo;

    // UI State
    public $isEditing = false;

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address_street' => 'nullable|string|max:255',
            'address_city' => 'nullable|string|max:100',
            'address_postal_code' => 'nullable|string|max:20',
            'address_country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:1024', // 1MB Max
        ];
    }
    
    public function mount($id = null)
    {
        if ($id) {
            $this->loadPatient($id);
        } else {
            $this->prepareForCreate();
        }
    }

    public function prepareForCreate()
    {
        $this->reset([
            'patient', 'isEditing', 'photo',
            'first_name', 'last_name', 'email', 'phone', 'date_of_birth', 
            'address_street', 'address_city', 'address_postal_code', 'notes',
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'
        ]);
        $this->gender = 'other';
        $this->address_country = 'España';
        $this->isEditing = false;
    }
    
    public function loadPatient($id)
    {
        $patient = Contact::find($id);
        
        if (!$patient || $patient->professional_id !== auth()->user()->professional->id) {
            return redirect()->route('patients.index');
        }
        
        $this->patient = $patient;
        $this->isEditing = true;
        
        $this->first_name = $patient->first_name;
        $this->last_name = $patient->last_name;
        $this->email = $patient->email;
        $this->phone = $patient->phone;
        $this->date_of_birth = $patient->date_of_birth?->format('Y-m-d');
        $this->gender = $patient->gender ?? 'other';
        $this->address_street = $patient->address_street;
        $this->address_city = $patient->address_city;
        $this->address_postal_code = $patient->address_postal_code;
        $this->address_country = $patient->address_country;
        $this->emergency_contact_name = $patient->emergency_contact_name;
        $this->emergency_contact_phone = $patient->emergency_contact_phone;
        $this->emergency_contact_relationship = $patient->emergency_contact_relationship;
        $this->notes = $patient->notes;
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['professional_id'] = auth()->user()->professional->id;

        if ($this->photo) {
            $validated['profile_photo_path'] = $this->photo->store('patients-photos', 'public');
        }

        // Remove photo from validated array as it's not a column
        unset($validated['photo']);

        if ($this->isEditing) {
            $this->patient->update($validated);
            session()->flash('success', 'Paciente actualizado correctamente.');
        } else {
            Contact::create($validated);
            session()->flash('success', 'Paciente creado correctamente.');
        }
        
        return redirect()->route('patients.index');
    }

    public function render()
    {
        return view('livewire.dashboard.patients.patient-form');
    }
}
