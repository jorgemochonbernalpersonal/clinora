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
    
    // Form fields - Datos básicos
    public $first_name;
    public $last_name;
    public $dni;
    public $email;
    public $phone;
    public $date_of_birth;
    public $gender = 'other';
    public $marital_status;
    public $occupation;
    public $education_level;
    
    // Dirección
    public $address_street;
    public $address_city;
    public $address_postal_code;
    public $address_country = 'España';
    
    // Contacto de emergencia
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relationship;
    
    // Información clínica
    public $initial_consultation_reason;
    public $first_appointment_date;
    public $medical_history;
    public $psychiatric_history;
    public $current_medication;
    
    // Seguro médico
    public $insurance_company;
    public $insurance_policy_number;
    
    // Notas
    public $notes;
    
    // Marketing / Legal
    public $referral_source;
    public $data_protection_consent = false;
    public $tags;
    
    // File Upload
    public $photo;

    // UI State
    public $isEditing = false;

    protected function rules()
    {
        return [
            // Datos básicos obligatorios
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            
            // Datos básicos opcionales
            'dni' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,cohabiting,other',
            'occupation' => 'nullable|string|max:255',
            'education_level' => 'nullable|in:primary,secondary,vocational,university,postgraduate,other',
            
            // Dirección
            'address_street' => 'nullable|string|max:255',
            'address_city' => 'nullable|string|max:100',
            'address_postal_code' => 'nullable|string|max:20',
            'address_country' => 'nullable|string|max:100',
            
            // Contacto de emergencia
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            
            // Información clínica
            'initial_consultation_reason' => 'nullable|string',
            'first_appointment_date' => 'nullable|date',
            'medical_history' => 'nullable|string',
            'psychiatric_history' => 'nullable|string',
            'current_medication' => 'nullable|string',
            
            // Seguro médico
            'insurance_company' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
            
            // Notas
            'notes' => 'nullable|string',
            
            // Marketing / Legal
            'referral_source' => 'nullable|string|max:255',
            'data_protection_consent' => 'accepted',
            'photo' => 'nullable|image|max:1024', // 1MB Max
            'tags' => 'nullable|string',
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
            'first_name', 'last_name', 'dni', 'email', 'phone', 'date_of_birth',
            'gender', 'marital_status', 'occupation', 'education_level',
            'address_street', 'address_city', 'address_postal_code', 'address_country',
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
            'initial_consultation_reason', 'first_appointment_date',
            'medical_history', 'psychiatric_history', 'current_medication',
            'insurance_company', 'insurance_policy_number',
            'notes', 'referral_source', 'data_protection_consent', 'tags'
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
        $this->dni = $patient->dni;
        $this->email = $patient->email;
        $this->phone = $patient->phone;
        $this->date_of_birth = $patient->date_of_birth?->format('Y-m-d');
        $this->gender = $patient->gender ?? 'other';
        $this->marital_status = $patient->marital_status;
        $this->occupation = $patient->occupation;
        $this->education_level = $patient->education_level;
        $this->address_street = $patient->address_street;
        $this->address_city = $patient->address_city;
        $this->address_postal_code = $patient->address_postal_code;
        $this->address_country = $patient->address_country;
        $this->emergency_contact_name = $patient->emergency_contact_name;
        $this->emergency_contact_phone = $patient->emergency_contact_phone;
        $this->emergency_contact_relationship = $patient->emergency_contact_relationship;
        $this->initial_consultation_reason = $patient->initial_consultation_reason;
        $this->first_appointment_date = $patient->first_appointment_date?->format('Y-m-d');
        $this->medical_history = $patient->medical_history;
        $this->psychiatric_history = $patient->psychiatric_history;
        $this->current_medication = $patient->current_medication;
        $this->insurance_company = $patient->insurance_company;
        $this->insurance_policy_number = $patient->insurance_policy_number;
        $this->notes = $patient->notes;
        $this->referral_source = $patient->referral_source;
        $this->data_protection_consent = $patient->data_protection_consent;
        $this->tags = is_array($patient->tags) ? implode(', ', $patient->tags) : $patient->tags;
    }

    public function save()
    {
        // Check patient limits (only for new patients, not edits)
        if (!$this->isEditing) {
            $professional = auth()->user()->professional;
            $planLimits = app(\App\Core\Subscriptions\Services\PlanLimitsService::class);
            
            if (!$planLimits->canAddPatient($professional)) {
                $stats = $planLimits->getUsageStats($professional);
                
                session()->flash('error', sprintf(
                    '¡Has alcanzado el límite de %d pacientes de tu plan %s!',
                    $stats['patient_limit'],
                    $professional->subscription_plan->label()
                ));
                
                session()->flash('upgrade_required', [
                    'feature' => 'patient-limit',
                    'feature_name' => 'más pacientes',
                    'required_plan' => 'Pro',
                ]);
                
                return redirect()->route('patients.index');
            }
        }
        
        $validated = $this->validate();
        $validated['professional_id'] = auth()->user()->professional->id;

        if ($this->photo) {
            $validated['profile_photo_path'] = $this->photo->store('patients-photos', 'public');
        }

        // Remove photo from validated array as it's not a column
        unset($validated['photo']);

        // Handle tags (convert comma string to array)
        if ($this->tags) {
            $validated['tags'] = array_map('trim', explode(',', $this->tags));
        }

        // Handle GDPR Timestamp
        if ($this->data_protection_consent) {
             // If creating or if consent was not previously given
             if (!$this->isEditing || !$this->patient->data_protection_consent) {
                 $validated['data_protection_consent_at'] = now();
             }
             $validated['data_protection_consent'] = true;
        }

        if ($this->isEditing) {
            $this->patient->update($validated);
            session()->flash('success', 'Paciente actualizado correctamente.');
        } else {
            Contact::create($validated);
            session()->flash('success', '¡Paciente creado correctamente!');
        }
        
        return redirect()->route('patients.index');
    }

    public function render()
    {
        return view('livewire.dashboard.patients.patient-form');
    }
}
