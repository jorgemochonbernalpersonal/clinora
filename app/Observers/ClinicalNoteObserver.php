<?php

namespace App\Observers;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;

class ClinicalNoteObserver
{
    public function created(ClinicalNote $note): void
    {
        // Track onboarding progress - first note
        $onboardingService = app(\App\Services\OnboardingService::class);
        $onboardingService->markStepCompleted($note->professional->user, 'first_note');
    }
}
