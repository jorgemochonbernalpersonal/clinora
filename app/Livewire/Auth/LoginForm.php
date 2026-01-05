<?php

namespace App\Livewire\Auth;

use App\Core\Authentication\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    
    public ?string $errorMessage = null;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();
        
        $this->errorMessage = null;

        try {
            // Preparar datos validados (Livewire ya validó con $this->validate())
            $credentials = [
                'email' => $this->email,
                'password' => $this->password,
            ];

            // Llamar directamente al servicio
            $result = app(AuthService::class)->login($credentials);
            
            // Check if user has 2FA enabled
            if ($result['user']->two_factor_enabled && $result['user']->two_factor_secret) {
                // Store user ID and remember preference in session for 2FA verification
                session(['2fa_user_id' => $result['user']->id]);
                session(['2fa_remember' => $this->remember]);
                
                return redirect()->route('2fa.verify');
            }

            // Store token in session
            session(['api_token' => $result['token']]);
            session(['user' => $result['user']]);
            
            // Simple auth simulation for middleware
            auth()->loginUsingId($result['user']->id, $this->remember);
            
            return redirect()->route('psychologist.dashboard');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = $e->getMessage() ?? 'Credenciales incorrectas';
        } catch (\Exception $e) {
            Log::error('[LOGIN_FORM] Error inesperado', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $this->errorMessage = 'Error al iniciar sesión. Por favor, intenta de nuevo.';
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
