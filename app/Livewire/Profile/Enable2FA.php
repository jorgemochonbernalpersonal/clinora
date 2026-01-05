<?php

namespace App\Livewire\Profile;

use App\Core\Authentication\Services\TwoFactorService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Enable2FA extends Component
{
    public bool $twoFactorEnabled = false;
    public ?string $secret = null;
    public ?string $qrCodeUrl = null;
    public array $recoveryCodes = [];
    public string $verificationCode = '';
    public bool $showRecoveryCodes = false;
    
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected TwoFactorService $twoFactorService;

    public function boot(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function mount()
    {
        $user = auth()->user();
        $this->twoFactorEnabled = $user->two_factor_enabled;
        
        if ($this->twoFactorEnabled && $user->two_factor_recovery_codes) {
            $this->recoveryCodes = json_decode($user->two_factor_recovery_codes, true) ?? [];
        }
    }

    public function startEnable2FA()
    {
        $this->reset(['successMessage', 'errorMessage', 'verificationCode']);
        
        try {
            $user = auth()->user();
            
            // Generate secret
            $this->secret = $this->twoFactorService->generateSecretKey();
            
            // Generate QR code URL
            $this->qrCodeUrl = $this->twoFactorService->getQRCodeUrl($user, $this->secret);
            
            // Save secret temporarily (not enabled yet)
            $user->update([
                'two_factor_secret' => $this->secret,
            ]);
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al iniciar la configuración de 2FA';
        }
    }

    public function verifyAndEnable()
    {
        $this->validate([
            'verificationCode' => 'required|string|size:6',
        ]);
        
        $this->reset(['successMessage', 'errorMessage']);

        try {
            $user = auth()->user();
            
            if ($this->twoFactorService->enable($user, $this->verificationCode)) {
                $this->twoFactorEnabled = true;
                $this->recoveryCodes = json_decode($user->fresh()->two_factor_recovery_codes, true);
                $this->showRecoveryCodes = true;
                $this->successMessage = 'Autenticación de dos factores activada exitosamente';
                
                // Clear QR code and secret
                $this->secret = null;
                $this->qrCodeUrl = null;
                $this->verificationCode = '';
            } else {
                $this->errorMessage = 'Código de verificación incorrecto';
            }
            
        } catch (\Exception $e) {
            Log::error('[2FA] Error al verificar código 2FA', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            
            $this->errorMessage = 'Error al verificar el código';
        }
    }

    public function disable2FA()
    {
        $this->reset(['successMessage', 'errorMessage']);
        
        try {
            $user = auth()->user();
            
            $this->twoFactorService->disable($user);
            
            $this->twoFactorEnabled = false;
            $this->recoveryCodes = [];
            $this->showRecoveryCodes = false;
            $this->successMessage = 'Autenticación de dos factores desactivada';
            
        } catch (\Exception $e) {
            Log::error('[2FA] Error al desactivar 2FA', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            
            $this->errorMessage = 'Error al desactivar 2FA';
        }
    }

    public function cancelSetup()
    {
        $this->secret = null;
        $this->qrCodeUrl = null;
        $this->verificationCode = '';
        $this->reset(['successMessage', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.profile.enable2-f-a');
    }
}
