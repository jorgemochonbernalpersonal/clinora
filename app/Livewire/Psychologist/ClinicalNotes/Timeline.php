<?php

namespace App\Livewire\Psychologist\ClinicalNotes;

use App\Core\ClinicalNotes\Models\ClinicalNote;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class Timeline extends Component
{
    use WithPagination;
    public $contactId = null; // Filter logic
    public $search = ''; // Patient search
    public $selectedPatientId = null; // UI selection
    public $limit = 20;

    public function updatedSearch()
    {
        $this->resetPage(); // If pagination exists, though not used here yet
    }

    public function selectPatient($id)
    {
        if ($this->selectedPatientId === $id) {
            $this->selectedPatientId = null; // Toggle off
            $this->contactId = null;
        } else {
            $this->selectedPatientId = $id;
            $this->contactId = $id; // Updates query
        }
    }

    public function getPatientsProperty()
    {
        return \App\Core\Contacts\Models\Contact::query()
            ->where('professional_id', auth()->user()->professional->id)
            ->where(function($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name', 'like', '%'.$this->search.'%')
                  ->orWhere('dni', 'like', '%'.$this->search.'%');
            })
            ->orderBy('last_name')
            ->limit(50)
            ->get();
    }
    
    public function getNotesProperty()
    {
        return ClinicalNote::query()
            ->where('professional_id', auth()->user()->professional->id)
            ->when($this->contactId, fn($q) => $q->where('contact_id', $this->contactId))
            ->with(['contact', 'appointment'])
            ->orderBy('session_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get()
            ->groupBy(function ($note) {
                return $note->session_date->format('F Y'); // Group by Month Year
            });
    }

    public function render()
    {
        return view('livewire.psychologist.clinical-notes.timeline', [
            'groupedNotes' => $this->notes
        ]);
    }
}
