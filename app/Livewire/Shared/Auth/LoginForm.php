<?php

namespace App\Livewire\Shared\Auth;

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

        try {
            $authService = app(AuthService::class);
            $result = $authService->login($this->email, $this->password, $this->remember);

            if ($result['success']) {
                session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }

            $this->errorMessage = $result['message'] ?? 'Credenciales inválidas';
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            $this->errorMessage = 'Error al iniciar sesión. Por favor, inténtalo de nuevo.';
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}

