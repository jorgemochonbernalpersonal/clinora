<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    protected $messages = [
        'current_password.required' => 'La contraseña actual es obligatoria',
        'password.required' => 'La nueva contraseña es obligatoria',
        'password.confirmed' => 'Las contraseñas no coinciden',
    ];

    public function changePassword()
    {
        $this->validate();
        
        $this->successMessage = null;
        $this->errorMessage = null;

        try {
            $user = auth()->user();

            // Verify current password
            if (!Hash::check($this->current_password, $user->password)) {
                $this->errorMessage = 'La contraseña actual es incorrecta';
                return;
            }

            // Update password and track change date
            $user->update([
                'password' => Hash::make($this->password),
                'password_changed_at' => now(),
            ]);

            $this->successMessage = 'Contraseña cambiada exitosamente';
            
            // Reset form
            $this->reset(['current_password', 'password', 'password_confirmation']);

        } catch (\Exception $e) {
            Log::error('[CHANGE_PASSWORD] Error al cambiar contraseña', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            
            $this->errorMessage = 'Error al cambiar la contraseña. Intenta de nuevo.';
        }
    }

    public function render()
    {
        return view('livewire.profile.change-password');
    }
}
