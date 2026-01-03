<?php

namespace App\Livewire;

use App\Shared\Services\LogViewerService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class LogViewer extends Component
{
    use WithPagination;
    
    public string $selectedDate;
    public string $logLevel = 'all';
    public string $search = '';
    public array $availableDates = [];
    public string $dateLabel = '';
    public int $perPage = 50;
    
    protected $queryString = [
        'selectedDate' => ['except' => ''],
        'logLevel' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function mount(LogViewerService $logService)
    {
        // Set default date to today
        $this->selectedDate = $this->selectedDate ?: Carbon::today()->format('Y-m-d');
        
        // Load available dates
        $this->loadAvailableDates($logService);
    }

    public function render(LogViewerService $logService)
    {
        $logData = $logService->getLogsByDate($this->selectedDate);
        $this->dateLabel = $logService->getDateLabel($this->selectedDate);
        
        // Apply filters
        $filteredLogs = $logService->filterLogs(
            $logData['logs'],
            $this->logLevel,
            $this->search
        );

        return view('livewire.log-viewer', [
            'logs' => $filteredLogs->simplePaginate($this->perPage),
            'stats' => $logData['stats'],
            'logExists' => $logData['exists'],
            'dateLabel' => $this->dateLabel,
        ]);
    }

    public function updatedSelectedDate(LogViewerService $logService)
    {
        $this->dateLabel = $logService->getDateLabel($this->selectedDate);
        $this->resetFilters();
    }

    public function updatedLogLevel()
    {
        // Trigger re-render
    }

    public function updatedSearch()
    {
        // Trigger re-render
    }

    public function resetFilters()
    {
        $this->logLevel = 'all';
        $this->search = '';
    }

    public function downloadLog(LogViewerService $logService)
    {
        $filepath = $logService->downloadLog($this->selectedDate);
        
        if ($filepath) {
            return response()->download($filepath);
        }
        
        $this->dispatch('show-toast', [
            'type' => 'error',
            'message' => 'No se pudo descargar el archivo de log.'
        ]);
    }

    private function loadAvailableDates(LogViewerService $logService)
    {
        $this->availableDates = $logService->getAvailableLogDates()
            ->map(fn($item) => $item['date'])
            ->toArray();
    }

    public function changeDate(string $date)
    {
        $this->selectedDate = $date;
        $this->resetFilters();
        $this->resetPage();
    }
    
    public function updatingLogLevel()
    {
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
}
