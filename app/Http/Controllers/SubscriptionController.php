<?php

namespace App\Http\Controllers;

use App\Core\Subscriptions\Services\PlanLimitsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function __construct(
        private PlanLimitsService $planLimitsService
    ) {}

    /**
     * Show subscription management page
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $professional = $user->professional;
        
        // Current stats
        $stats = $this->planLimitsService->getUsageStats($professional);
        
        // Monthly history (last 6 months)
        $monthlyHistory = $this->getMonthlyHistory($professional, 6);
        
        // Calculate estimated cost for this month
        $estimatedCost = $this->calculateEstimatedCost($professional, $stats['active_patients']);
        
        return view('subscription.index', [
            'professional' => $professional,
            'stats' => $stats,
            'monthlyHistory' => $monthlyHistory,
            'estimatedCost' => $estimatedCost,
        ]);
    }
    
    /**
     * Get monthly usage history
     */
    private function getMonthlyHistory($professional, int $months): array
    {
        $history = [];
        
        for ($i = 0; $i < $months; $i++) {
            $startDate = Carbon::now()->subMonths($i)->startOfMonth();
            $endDate = Carbon::now()->subMonths($i)->endOfMonth();
            
            // Count active patients for that month
            $activePatients = \App\Core\Contacts\Models\Contact::where('professional_id', $professional->id)
                ->where(function($query) use ($startDate, $endDate) {
                    $query->whereHas('appointments', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_time', [$startDate, $endDate]);
                    })
                    ->orWhereHas('clinicalNotes', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
                })
                ->count();
            
            $cost = $this->calculateEstimatedCost($professional, $activePatients);
            
            $history[] = [
                'month' => $startDate->format('M Y'),
                'month_full' => $startDate->format('F Y'),
                'active_patients' => $activePatients,
                'cost' => $cost,
            ];
        }
        
        return array_reverse($history);
    }
    
    /**
     * Calculate estimated cost
     */
    private function calculateEstimatedCost($professional, int $activePatients): float
    {
        if ($professional->isOnFreePlan()) {
            return 0;
        }
        
        $pricePerPatient = $professional->isOnProPlan() ? 1 : 2;
        return $activePatients * $pricePerPatient;
    }
    
    /**
     * Update email preferences
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_limit_alerts' => 'boolean',
            'email_weekly_summary' => 'boolean',
            'email_marketing' => 'boolean',
        ]);
        
        $request->user()->update($validated);
        
        return back()->with('success', 'Preferencias actualizadas correctamente');
    }
}
