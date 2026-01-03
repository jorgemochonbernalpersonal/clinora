<?php

namespace App\Livewire\ClinicalNotes;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ClinicalNoteList extends Component
{
    public bool $showCreateModal = false;
    public array $patients = [];
    
    // Form fields
    public string $contact_id = '';
    public string $subjective = '';
    public string $objective = '';
    public string $assessment = '';
    public string $plan = '';
    public string $risk_assessment = 'low';
    
    protected $rules = [
        'contact_id' => 'required',
        'subjective' => 'required|string',
        'objective' => 'required|string',
        'assessment' => 'required|string',
        'plan' => 'required|string',
        'risk_assessment' => 'required|in:none,low,medium,high,critical',
    ];

    public function mount()
    {
        $this->loadPatients();
    }

    private function loadPatients()
    {
        try {
            $response = Http::withToken(session('api_token'))
                ->get(url('/api/v1/contacts'), ['active' => 1]);

            if ($response->successful()) {
                $this->patients = $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            // Handle error
        }
    }

    public function getClinicalNotes()
    {
        try {
            $response = Http::withToken(session('api_token'))
                ->get(url('/api/v1/clinical-notes'), [
                    'recent' => 1,
                ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            // Handle error
        }
        
        return [];
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function createClinicalNote()
    {
        $this->validate();

        try {
            $response = Http::withToken(session('api_token'))
                ->post(url('/api/v1/clinical-notes'), [
                    'contact_id' => $this->contact_id,
                    'subjective' => $this->subjective,
                    'objective' => $this->objective,
                    'assessment' => $this->assessment,
                    'plan' => $this->plan,
                    'risk_assessment' => $this->risk_assessment,
                ]);

            if ($response->successful()) {
                $this->closeCreateModal();
                session()->flash('success', 'Nota clínica creada exitosamente');
            } else {
                $data = $response->json();
                session()->flash('error', $data['message'] ?? 'Error al crear nota');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error de conexión');
        }
    }

    private function resetForm()
    {
        $this->contact_id = '';
        $this->subjective = '';
        $this->objective = '';
        $this->assessment = '';
        $this->plan = '';
        $this->risk_assessment = 'low';
    }

    public function render()
    {
        return view('livewire.clinical-notes.clinical-note-list', [
            'notes' => $this->getClinicalNotes(),
        ]);
    }
}
