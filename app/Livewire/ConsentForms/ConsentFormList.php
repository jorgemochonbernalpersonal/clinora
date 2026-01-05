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

    public function render()
    {
        return view('livewire.consent-forms.consent-form-list', [
            'consentForms' => $this->consentForms,
            'availableTypes' => $this->availableTypes,
        ]);
    }
}
