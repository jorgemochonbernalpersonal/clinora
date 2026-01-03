<?php

namespace App\Livewire\Dashboard\ClinicalNotes;

use App\Core\ClinicalNotes\Models\ClinicalNote;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Livewire\Component;

class Timeline extends Component
{
    public $contactId = null; // Optional: Filter by specific patient
    public $limit = 10;
    
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
        return view('livewire.dashboard.clinical-notes.timeline', [
            'groupedNotes' => $this->notes
        ]);
    }
}
