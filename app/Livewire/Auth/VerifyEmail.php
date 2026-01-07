<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class VerifyEmail extends Component
{
    public ?string $successMessage = null;
    public ?string $errorMessage = null;
    public bool $isVerified = false;

    public function mount()
    {
        // If already verified, redirect immediately
        if (auth()->user()->hasVerifiedEmail()) {
            $this->isVerified = true;
        }
    }

    public function checkVerificationStatus()
    {
        try {
            // Refresh user from database
            auth()->user()->refresh();
            
            if (auth()->user()->hasVerifiedEmail()) {
                $this->isVerified = true;
            }
        } catch (\Exception $e) {
            // Silently fail - user might have logged out in another tab
            Log::debug('[VERIFY_EMAIL] Error checking verification status', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function resend()
    {
        $this->reset(['successMessage', 'errorMessage']);

        try {
            $user = auth()->user();

            if ($user->hasVerifiedEmail()) {
                $this->isVerified = true;
                return;
            }

            $user->sendEmailVerificationNotification();

            $this->successMessage = 'Email de verificación enviado. Revisa tu bandeja de entrada.';

        } catch (\Exception $e) {
            Log::error('[VERIFY_EMAIL] Error al reenviar email', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $this->errorMessage = 'Error al enviar el email. Intenta de nuevo.';
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
