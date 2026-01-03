<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmailVerificationModal extends Component
{
    public bool $show = false;
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function mount()
    {
        // Mostrar el modal si el usuario no ha verificado su email
        $this->show = auth()->check() && auth()->user() && !auth()->user()->hasVerifiedEmail();
    }

    public function updatedShow()
    {
        // Si el usuario verifica su email, ocultar el modal
        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            $this->show = false;
        }
    }

    public function checkVerificationStatus()
    {
        if (!auth()->check() || !auth()->user()) {
            $this->show = false;
            return;
        }

        // Recargar el usuario para obtener el estado actualizado
        auth()->user()->refresh();
        
        if (auth()->user()->hasVerifiedEmail()) {
            $this->show = false;
            session()->flash('success', '¡Email verificado exitosamente!');
            $this->dispatch('email-verified');
        }
    }

    public function resend()
    {
        $this->reset(['successMessage', 'errorMessage']);

        if (!auth()->check() || !auth()->user()) {
            $this->errorMessage = 'No estás autenticado';
            return;
        }

        try {
            $user = auth()->user();

            if ($user->hasVerifiedEmail()) {
                $this->errorMessage = 'Tu email ya está verificado';
                $this->show = false;
                return;
            }

            $user->sendEmailVerificationNotification();

            $this->successMessage = 'Email de verificación enviado. Revisa tu bandeja de entrada.';

            Log::info('[EMAIL_VERIFICATION_MODAL] Email de verificación reenviado', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

        } catch (\Exception $e) {
            Log::error('[EMAIL_VERIFICATION_MODAL] Error al reenviar email', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $this->errorMessage = 'Error al enviar el email. Intenta de nuevo.';
        }
    }

    public function close()
    {
        $this->show = false;
    }

    public function render()
    {
        // Actualizar el estado de show antes de renderizar
        if (auth()->check() && auth()->user()) {
            $this->show = !auth()->user()->hasVerifiedEmail();
        } else {
            $this->show = false;
        }
        
        return view('livewire.auth.email-verification-modal');
    }

    public function poll()
    {
        // Este método se puede llamar periódicamente para verificar el estado
        $this->checkVerificationStatus();
    }
}

