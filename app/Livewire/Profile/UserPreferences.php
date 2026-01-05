<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class UserPreferences extends Component
{
    public string $theme;
    public bool $notifications_enabled;
    public bool $email_notifications;
    public bool $sms_notifications;
    
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function mount()
    {
        $user = auth()->user();
        $this->theme = $user->theme ?? 'light';
        $this->notifications_enabled = $user->notifications_enabled ?? true;
        $this->email_notifications = $user->email_notifications ?? true;
        $this->sms_notifications = $user->sms_notifications ?? false;
    }

    public function save()
    {
        $this->reset(['successMessage', 'errorMessage']);

        try {
            auth()->user()->update([
                'theme' => $this->theme,
                'notifications_enabled' => $this->notifications_enabled,
                'email_notifications' => $this->email_notifications,
                'sms_notifications' => $this->sms_notifications,
            ]);

            $this->successMessage = 'Preferencias actualizadas correctamente';

        } catch (\Exception $e) {
            \Log::error('[USER_PREFERENCES] Error al actualizar preferencias', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $this->errorMessage = 'Error al actualizar las preferencias';
        }
    }

    public function render()
    {
        return view('livewire.profile.user-preferences');
    }
}
