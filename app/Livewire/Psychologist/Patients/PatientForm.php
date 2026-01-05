<?php

namespace App\Livewire\Psychologist\Patients;

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
            'dni' => ['nullable', 'string', 'max:20', function ($attribute, $value, $fail) {
                if (!empty($value) && !$this->validateDni($value)) {
                    $fail('El formato del DNI/NIE no es válido. Debe ser 8 dígitos + letra (DNI) o X/Y/Z + 7 dígitos + letra (NIE).');
                }
            }],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
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

    /**
     * Validate Spanish DNI/NIE format
     */
    protected function validateDni($dni): bool
    {
        if (empty($dni)) {
            return true; // Optional field
        }

        $dni = strtoupper(trim($dni));
        
        // DNI: 8 digits + 1 letter
        if (preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            $number = substr($dni, 0, 8);
            $letter = substr($dni, 8, 1);
            $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
            $expectedLetter = $letters[$number % 23];
            return $letter === $expectedLetter;
        }
        
        // NIE: X/Y/Z + 7 digits + 1 letter
        if (preg_match('/^[XYZ][0-9]{7}[A-Z]$/', $dni)) {
            $firstChar = substr($dni, 0, 1);
            $number = substr($dni, 1, 7);
            $letter = substr($dni, 8, 1);
            
            // Replace X/Y/Z with 0/1/2
            $number = str_replace(['X', 'Y', 'Z'], ['0', '1', '2'], $firstChar) . $number;
            $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
            $expectedLetter = $letters[$number % 23];
            return $letter === $expectedLetter;
        }
        
        return false;
    }

    /**
     * Get computed age from date of birth
     */
    public function getAgeProperty()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        try {
            return \Carbon\Carbon::parse($this->date_of_birth)->age;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.psychologist.patients.patient-form');
    }
}
