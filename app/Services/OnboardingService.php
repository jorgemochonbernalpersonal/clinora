<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class OnboardingService
{
    private const STEPS = [
        'welcome_seen' => 'Bienvenida vista',
        'first_patient' => 'Primer paciente creado',
        'first_appointment' => 'Primera cita programada',
        'first_note' => 'Primera nota clínica',
    ];

    public function markStepCompleted(User $user, string $step): void
    {
        if (!isset(self::STEPS[$step])) {
            return;
        }

        DB::table('onboarding_progress')->updateOrInsert(
            ['user_id' => $user->id, 'step' => $step],
            [
                'completed' => true,
                'completed_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function getProgress(User $user): array
    {
        $completed = DB::table('onboarding_progress')
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->pluck('step')
            ->toArray();

        return [
            'steps' => self::STEPS,
            'completed' => $completed,
            'total' => count(self::STEPS),
            'completed_count' => count($completed),
            'percentage' => round((count($completed) / count(self::STEPS)) * 100),
            'is_complete' => count($completed) === count(self::STEPS),
        ];
    }

    public function isOnboardingComplete(User $user): bool
    {
        $progress = $this->getProgress($user);
        return $progress['is_complete'];
    }

    public function shouldShowWelcome(User $user): bool
    {
        return !DB::table('onboarding_progress')
            ->where('user_id', $user->id)
            ->where('step', 'welcome_seen')
            ->exists();
    }
}
