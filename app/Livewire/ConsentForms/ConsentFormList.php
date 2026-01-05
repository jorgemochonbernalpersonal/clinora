<?php

namespace App\Livewire\ConsentForms;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Services\ConsentFormService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class ConsentFormList extends Component
{
    use WithPagination;

    public ?int $contactId = null;
    public ?string $consentType = null;
    public string $status = 'all'; // all, signed, pending, revoked
    public string $search = '';

    protected $queryString = [
        'contactId' => ['except' => ''],
        'consentType' => ['except' => ''],
        'status' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function mount(?int $contactId = null)
    {
        $this->contactId = $contactId;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function filterByStatus(string $status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function getConsentFormsProperty()
    {
        $query = ConsentForm::where('professional_id', auth()->user()->professional->id)
            ->with(['contact', 'professional']);

        if ($this->contactId) {
            $query->where('contact_id', $this->contactId);
        }

        if ($this->consentType) {
            $query->where('consent_type', $this->consentType);
        }

        switch ($this->status) {
            case 'signed':
                $query->signed();
                break;
            case 'pending':
                $query->pending();
                break;
            case 'revoked':
                $query->revoked();
                break;
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('consent_title', 'like', "%{$this->search}%")
                  ->orWhereHas('contact', function ($q) {
                      $q->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%");
                  });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getAvailableTypesProperty()
    {
        $service = app(ConsentFormService::class);
        return $service->getAvailableTypes(auth()->user()->professional);
    }

    public function delete(int $id)
    {
        $consentForm = ConsentForm::where('professional_id', auth()->user()->professional->id)
            ->findOrFail($id);

        if ($consentForm->isSigned()) {
            session()->flash('error', 'No se puede eliminar un consentimiento firmado');
            return;
        }

        $consentForm->delete();
        session()->flash('success', 'Consentimiento eliminado exitosamente');
    }
    
    /**
     * Send email with PDF to patient directly from list
     */
    public function sendEmail(int $id)
    {
        $consentForm = ConsentForm::where('professional_id', auth()->user()->professional->id)
            ->with('contact')
            ->findOrFail($id);

        // Validar que el consentimiento esté firmado
        if (!$consentForm->isSigned()) {
            session()->flash('error', 'Solo se pueden enviar emails de consentimientos firmados');
            return;
        }

        // Validar que no esté revocado
        if ($consentForm->isRevoked()) {
            session()->flash('error', 'No se pueden enviar emails de consentimientos revocados');
            return;
        }

        // Validar que el paciente tenga email
        if (empty($consentForm->contact->email)) {
            session()->flash('error', 'El paciente no tiene email registrado. Por favor, añade un email en el perfil del paciente.');
            return;
        }

        try {
            // Enviar email con el PDF adjunto
            \Mail::to($consentForm->contact->email)
                ->send(new \App\Mail\ConsentFormDelivered($consentForm));
            
            // Marcar como entregado automáticamente
            if (!$consentForm->isDelivered()) {
                $consentForm->markAsDelivered();
            }
            
            // Activity log
            \Log::info('Email de consentimiento enviado desde lista', [
                'consent_form_id' => $consentForm->id,
                'user_id' => auth()->id(),
                'email_sent_to' => $consentForm->contact->email,
            ]);
            
            session()->flash('success', '✅ Email enviado exitosamente a ' . $consentForm->contact->email);
        } catch (\Exception $e) {
            session()->flash('error', '❌ Error al enviar el email: ' . $e->getMessage());
            \Log::error('Error sending consent email from list', [
                'consent_form_id' => $consentForm->id,
                'email' => $consentForm->contact->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    public function getCountsProperty()
    {
        $baseQuery = ConsentForm::where('professional_id', auth()->user()->professional->id);
        
        return [
            'all' => (clone $baseQuery)->count(),
            'signed' => (clone $baseQuery)->signed()->count(),
            'pending' => (clone $baseQuery)->pending()->count(),
            'revoked' => (clone $baseQuery)->revoked()->count(),
        ];
    }

    public function render()
    {
        return view('livewire.consent-forms.consent-form-list', [
            'consentForms' => $this->consentForms,
            'availableTypes' => $this->availableTypes,
            'counts' => $this->counts,
        ]);
    }
}
