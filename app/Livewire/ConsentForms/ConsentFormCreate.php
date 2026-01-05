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
    ];

    protected $messages = [
        'contactId.required' => 'Debe seleccionar un paciente',
        'contactId.exists' => 'El paciente seleccionado no existe',
        'consentType.required' => 'Debe seleccionar un tipo de consentimiento',
        'platform.required_if' => 'La plataforma es requerida para teleconsulta',
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
    }

    public function save()
    {
        $this->validate();

        try {
            $service = app(ConsentFormService::class);
            
            $data = [
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
