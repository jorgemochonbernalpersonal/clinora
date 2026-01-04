<?php

namespace App\Observers;

use App\Core\Contacts\Models\Contact;
use App\Core\Subscriptions\Services\PlanLimitsService;
use App\Mail\PatientLimitWarningMail;
use App\Mail\PatientLimitReachedMail;
use Illuminate\Support\Facades\Mail;

/**
 * Contact Observer
 * 
 * Monitors patient creation to send limit notification emails
 */
class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     */
    public function created(Contact $contact): void
    {
        $professional = $contact->professional;
        
        // Track onboarding progress - first patient
        $onboardingService = app(\App\Services\OnboardingService::class);
        $onboardingService->markStepCompleted($professional->user, 'first_patient');
        
        // Only process for Free plan users
        if (!$professional || !$professional->isOnFreePlan()) {
            return;
        }
        
        // Check if user has email limit alerts enabled
        if (!$professional->user->email_limit_alerts ?? true) {
            return;
        }
        
        $planLimits = app(PlanLimitsService::class);
        $stats = $planLimits->getUsageStats($professional);
        
        $currentPatients = $stats['total_patients'];
        $limit = $stats['patient_limit'];
        
        if ($limit === null) {
            return; // No limit for this plan
        }
        
        // Calculate percentage
        $percentage = ($currentPatients / $limit) * 100;
        
        // Send warning email at 66% (2 out of 3 for free plan)
        if ($percentage >= 66 && $percentage < 100) {
            $this->sendWarningEmailIfNotSent($professional, $currentPatients, $limit);
        }
        
        // Send limit reached email at 100% (3 out of 3)
        if ($percentage >= 100) {
            $this->sendLimitReachedEmailIfNotSent($professional, $limit);
        }
    }
    
    /**
     * Send warning email only once
     */
    private function sendWarningEmailIfNotSent($professional, $currentPatients, $limit): void
    {
        $metadataKey = 'limit_warning_email_sent';
        
        if ($professional->user->hasMetadata($metadataKey)) {
            return; // Already sent
        }
        
        Mail::to($professional->user->email)
            ->send(new PatientLimitWarningMail($professional, $currentPatients, $limit));
        
        $professional->user->setMetadata($metadataKey, now()->toDateTimeString());
    }
    
    /**
     * Send limit reached email only once
     */
    private function sendLimitReachedEmailIfNotSent($professional, $limit): void
    {
        $metadataKey = 'limit_reached_email_sent';
        
        if ($professional->user->hasMetadata($metadataKey)) {
            return; // Already sent
        }
        
        Mail::to($professional->user->email)
            ->send(new PatientLimitReachedMail($professional, $limit));
        
        $professional->user->setMetadata($metadataKey, now()->toDateTimeString());
    }
}
