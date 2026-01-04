<?php

namespace App\Core\Subscriptions\Services;

use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Shared\Enums\SubscriptionPlan;
use Carbon\Carbon;

/**
 * Plan Limits Service
 * 
 * Central service to manage subscription-based feature limits and restrictions.
 * Implements hard blocks with grandfathering for existing users.
 */
class PlanLimitsService
{
    // Feature identifiers
    public const FEATURE_TELECONSULTA = 'teleconsulta';
    public const FEATURE_EVALUACIONES = 'evaluaciones';
    public const FEATURE_PORTAL_PACIENTE = 'portal_paciente';
    public const FEATURE_FACTURACION_AUTO = 'facturacion_automatica';
    public const FEATURE_MULTI_PROFESIONAL = 'multi_profesional';
    public const FEATURE_INFORMES_AVANZADOS = 'informes_avanzados';
    public const FEATURE_API_ACCESS = 'api_access';

    /**
     * Get the maximum number of patients allowed for a plan
     * 
     * @param SubscriptionPlan $plan
     * @return int|null null means unlimited
     */
    public function getPatientLimit(SubscriptionPlan $plan): ?int
    {
        return match($plan) {
            SubscriptionPlan::GRATIS => 3,
            SubscriptionPlan::PRO => null, // Unlimited
            SubscriptionPlan::EQUIPO => null, // Unlimited
        };
    }

    /**
     * Check if user has access to a specific feature
     * 
     * @param User $user
     * @param string $feature
     * @return bool
     */
    public function hasFeatureAccess(User $user, string $feature): bool
    {
        $plan = $user->professional?->subscription_plan;
        
        if (!$plan) {
            return false;
        }

        return match($feature) {
            // Basic features - available to all plans
            self::FEATURE_TELECONSULTA => $plan !== SubscriptionPlan::GRATIS,
            self::FEATURE_EVALUACIONES => $plan !== SubscriptionPlan::GRATIS,
            self::FEATURE_PORTAL_PACIENTE => $plan !== SubscriptionPlan::GRATIS,
            self::FEATURE_FACTURACION_AUTO => $plan !== SubscriptionPlan::GRATIS,
            
            // Advanced features - only for EQUIPO plan
            self::FEATURE_MULTI_PROFESIONAL => $plan === SubscriptionPlan::EQUIPO,
            self::FEATURE_INFORMES_AVANZADOS => $plan === SubscriptionPlan::EQUIPO,
            self::FEATURE_API_ACCESS => $plan === SubscriptionPlan::EQUIPO,
            
            default => false,
        };
    }

    /**
     * Get the count of active patients for the current month
     * Active patient = has at least one appointment, clinical note, or activity in last 30 days
     * 
     * @param Professional $professional
     * @return int
     */
    public function getActivePatientCount(Professional $professional): int
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        // Count distinct contacts (patients) with activity in last 30 days
        $activeCount = \App\Core\Contacts\Models\Contact::where('professional_id', $professional->id)
            ->where(function($query) use ($thirtyDaysAgo) {
                // Has recent appointments
                $query->whereHas('appointments', function($q) use ($thirtyDaysAgo) {
                    $q->where('scheduled_at', '>=', $thirtyDaysAgo);
                })
                // Or has recent clinical notes
                ->orWhereHas('clinicalNotes', function($q) use ($thirtyDaysAgo) {
                    $q->where('created_at', '>=', $thirtyDaysAgo);
                });
            })
            ->count();

        return $activeCount;
    }

    /**
     * Check if professional can add a new patient
     * Implements grandfathering: users who already exceed limit can keep existing patients
     * but cannot add new ones
     * 
     * @param Professional $professional
     * @return bool
     */
    public function canAddPatient(Professional $professional): bool
    {
        // Pro and Equipo plans have unlimited patients
        if (!$professional->isOnFreePlan()) {
            return true;
        }

        $limit = $this->getPatientLimit($professional->subscription_plan);
        
        if ($limit === null) {
            return true; // Unlimited
        }

        // Get total patient (contact) count
        $totalPatients = \App\Core\Contacts\Models\Contact::where('professional_id', $professional->id)
            ->where('is_active', true)
            ->count();
        
        // Grandfathering: If they already have more than the limit,
        // they can keep them but cannot add more
        if ($totalPatients >= $limit) {
            return false;
        }

        return true;
    }

    /**
     * Get usage statistics for a professional
     * 
     * @param Professional $professional
     * @return array
     */
    public function getUsageStats(Professional $professional): array
    {
        $activePatients = $this->getActivePatientCount($professional);
        $totalPatients = \App\Core\Contacts\Models\Contact::where('professional_id', $professional->id)
            ->where('is_active', true)
            ->count();
        $limit = $this->getPatientLimit($professional->subscription_plan);

        return [
            'active_patients' => $activePatients,
            'total_patients' => $totalPatients,
            'patient_limit' => $limit,
            'can_add_patient' => $this->canAddPatient($professional),
            'is_at_limit' => $limit !== null && $totalPatients >= $limit,
            'percentage_used' => $limit ? round(($totalPatients / $limit) * 100, 1) : 0,
        ];
    }

    /**
     * Get human-readable feature name
     * 
     * @param string $feature
     * @return string
     */
    public function getFeatureName(string $feature): string
    {
        return match($feature) {
            self::FEATURE_TELECONSULTA => 'Teleconsulta',
            self::FEATURE_EVALUACIONES => 'Evaluaciones Psicológicas',
            self::FEATURE_PORTAL_PACIENTE => 'Portal del Paciente',
            self::FEATURE_FACTURACION_AUTO => 'Facturación Automática',
            self::FEATURE_MULTI_PROFESIONAL => 'Gestión Multi-Profesional',
            self::FEATURE_INFORMES_AVANZADOS => 'Informes Avanzados',
            self::FEATURE_API_ACCESS => 'Acceso API',
            default => 'Función Premium',
        };
    }

    /**
     * Get the minimum plan required for a feature
     * 
     * @param string $feature
     * @return SubscriptionPlan
     */
    public function getRequiredPlan(string $feature): SubscriptionPlan
    {
        return match($feature) {
            self::FEATURE_MULTI_PROFESIONAL,
            self::FEATURE_INFORMES_AVANZADOS,
            self::FEATURE_API_ACCESS => SubscriptionPlan::EQUIPO,
            
            default => SubscriptionPlan::PRO,
        };
    }
}
