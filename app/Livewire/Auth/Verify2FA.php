<?php

namespace App\Livewire\Auth;

use App\Core\Authentication\Services\TwoFactorService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Verify2FA extends Component
{
    public string $code = '';
    public bool $useRecoveryCode = false;
    public ?string $errorMessage = null;

    protected $rules = [
        'code' => 'required|string|min:6',
    ];

    public function verify()
    {
        $this->validate();
        
        $this->errorMessage = null;

        try {
            $userId = session('2fa_user_id');
            $remember = session('2fa_remember', false);
            
            if (!$userId) {
                return redirect()->route('login');
            }

            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                return redirect()->route('login');
            }

            $twoFactorService = app(TwoFactorService::class);
            $valid = false;

            if ($this->useRecoveryCode) {
                // Verify recovery code
                $valid = $twoFactorService->verifyRecoveryCode($user, $this->code);
                
                if ($valid) {
                    Log::info('[2FA_LOGIN] Código de recuperación usado', [
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                // Verify TOTP code
                $valid = $twoFactorService->verify($user->two_factor_secret, $this->code);
            }

            if ($valid) {
                // Clear 2FA session data
                session()->forget(['2fa_user_id', '2fa_remember']);
                
                // Complete login
                $token = $user->createToken('auth-token')->plainTextToken;
                session(['api_token' => $token]);
                session(['user' => $user]);
                auth()->loginUsingId($user->id, $remember);

                Log::info('[2FA_LOGIN] Login completado con 2FA', [
                    'user_id' => $user->id,
                ]);

                return redirect()->route('dashboard');
            } else {
                $this->errorMessage = $this->useRecoveryCode 
                    ? 'Código de recuperación inválido'
                    : 'Código de verificación incorrecto';
                
                Log::warning('[2FA_LOGIN] Código 2FA incorrecto', [
                    'user_id' => $user->id,
                    'use_recovery' => $this->useRecoveryCode,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('[2FA_LOGIN] Error en verificación 2FA', [
                'error' => $e->getMessage(),
            ]);
            
            $this->errorMessage = 'Error al verificar el código';
        }
    }

    public function toggleRecoveryCode()
    {
        $this->useRecoveryCode = !$this->useRecoveryCode;
        $this->reset(['code', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.auth.verify2-f-a');
    }
}
