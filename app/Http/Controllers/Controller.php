<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Shared\Traits\Loggable;

abstract class Controller
{
    use Loggable;

    /**
     * Get professional_id for filtering based on user role
     * 
     * Returns:
     * - null for admin (can see all)
     * - professional_id for professional/assistant (filtered view)
     * 
     * @param User $user
     * @return int|null
     */
    protected function getProfessionalIdForUser(User $user): ?int
    {
        // Admin role can see all data (no filter)
        if ($user->hasRole('admin')) {
            return null;
        }

        // Assistant role sees data from assigned professional
        // TODO: Implement when assistant role is added
        // if ($user->hasRole('assistant') && $user->assistant) {
        //     return $user->assistant->professional_id;
        // }

        // Professional role sees only their own data
        if ($user->hasRole('professional') && $user->professional) {
            return $user->professional->id;
        }

        // Default: no access
        return null;
    }

    /**
     * Check if user can access all data (admin)
     * 
     * @param User $user
     * @return bool
     */
    protected function canAccessAllData(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
