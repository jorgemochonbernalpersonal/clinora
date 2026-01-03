<?php

namespace App\Livewire\Patients;

use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class PatientList extends Component
{
    public string $search = '';
    
    #[On('patient-saved')]
    public function render()
    {
        $patients = Contact::query()
            ->where('professional_id', auth()->user()->professional->id)
            ->when($this->search, function($q) {
                $q->searchByName($this->search);
            })
            ->latest()
            ->get();

        return view('livewire.patients.patient-list', [
            'patients' => $patients,
        ]);
    }
}
