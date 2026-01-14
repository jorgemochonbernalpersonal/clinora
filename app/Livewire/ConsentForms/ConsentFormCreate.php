<?php

namespace App\Livewire\ConsentForms;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Services\ConsentFormService;
use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class ConsentFormCreate extends Component
{
    public ?int $contactId = null;
    public string $consentType = ConsentForm::TYPE_INITIAL_TREATMENT;
    public ?string $consentTitle = null;
    
    // Additional fields for templates
    public ?string $treatmentDuration = null;
    public ?string $sessionFrequency = null;
    public ?int $sessionDuration = null;
    public array $treatmentTechniques = [];
    public ?string $platform = null;
    public ?string $securityInfo = null;
    public bool $recordingConsent = false;
    
    // For minors
    public ?string $legalGuardianName = null;
    public ?string $legalGuardianRelationship = null;
    public ?string $legalGuardianIdDocument = null;
    public bool $minorAssent = false;
    public ?string $minorAssentDetails = null;

    public $availableTypes = [];
    public $contacts = [];

    protected $rules = [
        'contactId' => 'required|exists:contacts,id',
        'consentType' => 'required|string',
        'consentTitle' => 'nullable|string|max:255',
        'treatmentDuration' => 'nullable|string',
        'sessionFrequency' => 'nullable|string',
        'sessionDuration' => 'nullable|integer|min:15|max:180',
        'treatmentTechniques' => 'nullable|array',
        'platform' => 'nullable|string|required_if:consentType,teleconsultation',
        'securityInfo' => 'nullable|string',
        'recordingConsent' => 'nullable|boolean',
        'legalGuardianName' => 'required_if:consentType,minors|string|max:255',
        'legalGuardianRelationship' => 'nullable|string|max:100',
        'legalGuardianIdDocument' => 'nullable|string|max:50',
        'minorAssent' => 'nullable|boolean',
        'minorAssentDetails' => 'nullable|string',
    ];

    protected $messages = [
        'contactId.required' => 'Debe seleccionar un paciente',
        'contactId.exists' => 'El paciente seleccionado no existe',
        'consentType.required' => 'Debe seleccionar un tipo de consentimiento',
        'platform.required_if' => 'La plataforma es requerida para teleconsulta',
        'legalGuardianName.required_if' => 'El nombre del tutor legal es requerido para menores',
    ];

    public function mount(?int $contactId = null)
    {
        $this->contactId = $contactId;
        $this->loadData();
    }

    public function loadData()
    {
        $professional = auth()->user()->professional;
        
        // Load available types
        $service = app(ConsentFormService::class);
        $this->availableTypes = $service->getAvailableTypes($professional);

        // Load contacts
        $this->contacts = Contact::where('professional_id', $professional->id)
            ->where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Set default platform for teleconsultation
        if ($this->consentType === ConsentForm::TYPE_TELECONSULTATION && !$this->platform) {
            $this->platform = 'Clinora';
            $this->securityInfo = 'Cifrado end-to-end, servidores en UE, cumplimiento RGPD';
        }
    }

    public function updatedConsentType()
    {
        // Reset fields when type changes
        if ($this->consentType === ConsentForm::TYPE_TELECONSULTATION) {
            $this->platform = $this->platform ?: 'Clinora';
            $this->securityInfo = $this->securityInfo ?: 'Cifrado end-to-end, servidores en UE, cumplimiento RGPD';
        } else {
            $this->platform = null;
            $this->securityInfo = null;
        }

        if ($this->consentType !== ConsentForm::TYPE_MINORS) {
            $this->legalGuardianName = null;
            $this->legalGuardianRelationship = null;
            $this->legalGuardianIdDocument = null;
            $this->minorAssent = false;
            $this->minorAssentDetails = null;
        }
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Consent form validation failed', [
                'errors' => $e->errors(),
                'data' => $this->all(),
            ]);
            throw $e;
        }

        try {
            $service = app(ConsentFormService::class);
            $professional = auth()->user()->professional;
            
            $data = [
                'professional_id' => $professional->id,
                'contact_id' => $this->contactId,
                'consent_type' => $this->consentType,
                'consent_title' => $this->consentTitle,
                'treatment_duration' => $this->treatmentDuration,
                'session_frequency' => $this->sessionFrequency,
                'session_duration' => $this->sessionDuration,
                'treatment_techniques' => $this->treatmentTechniques,
                'platform' => $this->platform,
                'security_info' => $this->securityInfo,
                'recording_consent' => $this->recordingConsent,
                'legal_guardian_name' => $this->legalGuardianName,
                'legal_guardian_relationship' => $this->legalGuardianRelationship,
                'legal_guardian_id_document' => $this->legalGuardianIdDocument,
                'minor_assent' => $this->minorAssent,
                'minor_assent_details' => $this->minorAssentDetails,
            ];

            $consentForm = $service->create($data, auth()->id());

            session()->flash('success', 'Consentimiento creado exitosamente. El paciente debe firmarlo antes de iniciar el tratamiento.');

            return redirect(profession_route('consent-forms.show', ['id' => $consentForm->id]));
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el consentimiento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.consent-forms.consent-form-create', [
            'consentTypes' => ConsentForm::getConsentTypes(),
        ]);
    }
}
