<?php

namespace App\Livewire\Patients;

use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class PatientList extends Component
{
    use WithPagination;
    
    public string $search = '';
    
    /**
     * Reset pagination when search changes
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    #[On('patient-saved')]
    public function render()
    {
        $patients = Contact::query()
            ->where('professional_id', auth()->user()->professional->id)
            ->when($this->search, function($q) {
                $q->searchByName($this->search);
            })
            ->latest()
            ->paginate(20);

        return view('livewire.patients.patient-list', [
            'patients' => $patients,
        ]);
    }
}
