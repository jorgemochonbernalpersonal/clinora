<?php

namespace App\Livewire\Psychologist\ClinicalNotes;

use App\Core\ClinicalNotes\Models\ClinicalNote;
use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class ClinicalNoteForm extends Component
{
    public ?ClinicalNote $note = null;
    
    public $contact_id;
    public $session_date;
    public $session_number;
    public $duration_minutes = 60;
    
    // SOAP Fields
    public $subjective;
    public $objective;
    public $assessment;
    public $plan;
    
    public $risk_assessment = 'sin_riesgo';
    
    // UI State
    public $isEditing = false;
    public $contacts = [];

    protected function rules()
    {
        return [
            'contact_id' => 'required|exists:contacts,id',
            'session_date' => 'required|date',
            'session_number' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'subjective' => 'required|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'required|string',
            'risk_assessment' => 'required|in:sin_riesgo,riesgo_bajo,riesgo_medio,riesgo_alto,riesgo_inminente',
        ];
    }
    
    public function mount($id = null)
    {
        $this->contacts = Contact::where('professional_id', auth()->user()->professional->id)
            ->orderBy('first_name')
            ->get();
            
        if ($id) {
            $this->loadNote($id);
        } else {
            $this->prepareForCreate();
        }
    }

    public function prepareForCreate()
    {
        $this->reset(['note', 'isEditing', 'contact_id', 'subjective', 'objective', 'assessment', 'plan']);
        $this->risk_assessment = 'sin_riesgo';
        $this->session_date = now()->format('Y-m-d');
        $this->isEditing = false;
    }
    
    public function loadNote($id)
    {
        $note = ClinicalNote::find($id);
        
        if (!$note || $note->professional_id !== auth()->user()->professional->id) {
            return redirect()->route(profession_prefix() . '.clinical-notes.index');
        }
        
        $this->note = $note;
        $this->isEditing = true;
        
        $this->contact_id = $note->contact_id;
        $this->session_date = $note->session_date->format('Y-m-d');
        $this->session_number = $note->session_number;
        $this->duration_minutes = $note->duration_minutes;
        $this->subjective = $note->subjective;
        $this->objective = $note->objective;
        $this->assessment = $note->assessment;
        $this->plan = $note->plan;
        $this->risk_assessment = $note->risk_assessment ?? 'sin_riesgo';
    }
    
    // Auto-calculate session number when patient changes
    public function updatedContactId($value)
    {
        if ($value && !$this->isEditing) {
            $this->session_number = ClinicalNote::getNextSessionNumber($value);
        }
    }

    public function save()
    {
        $validated = $this->validate();
        
        $data = [
            'professional_id' => auth()->user()->professional->id,
            'contact_id' => $validated['contact_id'],
            'session_date' => $validated['session_date'],
            'session_number' => $validated['session_number'],
            'duration_minutes' => $validated['duration_minutes'],
            'subjective' => $validated['subjective'],
            'objective' => $validated['objective'],
            'assessment' => $validated['assessment'],
            'plan' => $validated['plan'],
            'risk_assessment' => $validated['risk_assessment'],
        ];

        if ($this->isEditing) {
            $this->note->update($data);
            session()->flash('success', 'Nota actualizada correctamente.');
        } else {
            ClinicalNote::create($data);
            session()->flash('success', 'Nota creada correctamente.');
        }
        
        return redirect()->route(profession_prefix() . '.clinical-notes.index');
    }

    public function render()
    {
        return view('livewire.psychologist.clinical-notes.clinical-note-form');
    }
}
