<?php

namespace App\Http\Controllers;

use App\Services\OnboardingService;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function __construct(
        private OnboardingService $onboardingService
    ) {}

    /**
     * Mark welcome as seen
     */
    public function welcomeSeen(Request $request)
    {
        $this->onboardingService->markStepCompleted(
            $request->user(),
            'welcome_seen'
        );

        return redirect()->route('dashboard')
            ->with('show_checklist', true);
    }
}
