<?php

namespace App\Http\Middleware;

use App\Core\Subscriptions\Services\PlanLimitsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check Feature Access Middleware
 * 
 * Verifies if the authenticated user has access to specific premium features
 * based on their subscription plan.
 * 
 * Usage: Route::middleware(['auth', 'feature:teleconsulta'])
 */
class CheckFeatureAccess
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'No autenticado');
        }

        $planLimits = app(PlanLimitsService::class);

        if (!$planLimits->hasFeatureAccess($user, $feature)) {
            $featureName = $planLimits->getFeatureName($feature);
            $requiredPlan = $planLimits->getRequiredPlan($feature);

            return redirect()->route('features.blocked', ['feature' => $feature])
                ->with('blocked_feature', [
                    'feature' => $feature,
                    'feature_name' => $featureName,
                    'required_plan' => $requiredPlan->label(),
                ]);
        }

        return $next($request);
    }
}
