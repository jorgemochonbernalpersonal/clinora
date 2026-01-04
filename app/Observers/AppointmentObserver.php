<?php

namespace App\Observers;

use App\Core\Appointments\Models\Appointment;

class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        // Track onboarding progress - first appointment
        $onboardingService = app(\App\Services\OnboardingService::class);
        $onboardingService->markStepCompleted($appointment->professional->user, 'first_appointment');
    }
}
