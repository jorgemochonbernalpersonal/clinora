<?php

namespace App\Livewire\Shared;

use App\Core\ConsentForms\Models\ConsentForm;
use Livewire\Component;

class ConsentFormsPendingCount extends Component
{
    public function getPendingCountProperty()
    {
        if (!auth()->check() || !auth()->user()->professional) {
            return 0;
        }

        return ConsentForm::where('professional_id', auth()->user()->professional->id)
            ->pending()
            ->count();
    }

    public function render()
    {
        return view('livewire.shared.consent-forms-pending-count');
    }
}

