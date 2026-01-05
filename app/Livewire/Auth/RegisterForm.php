<?php

namespace App\Livewire\Auth;

use App\Core\Authentication\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RegisterForm extends Component
{
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $phone = '';
    public string $professional_number = '';
    public bool $terms_accepted = false;
    
    public ?string $errorMessage = null;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'professional_number' => 'nullable|string|max:50',
        'password' => 'required|string|min:8|confirmed',
        'terms_accepted' => 'accepted',
    ];

    protected $messages = [
        'email.required' => 'El email es obligatorio',
        'email.email' => 'Introduce un email válido',
        'email.unique' => 'Este email ya está registrado',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'first_name.required' => 'El nombre es obligatorio',
        'last_name.required' => 'Los apellidos son obligatorios',
        'phone.required' => 'El teléfono es obligatorio',
        'terms_accepted.accepted' => 'Debes aceptar los términos y condiciones',
    ];

    public function register()
    {
        $this->validate();
        
        $this->errorMessage = null;

        try {
            // Preparar datos para el servicio
            $registerData = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'license_number' => $this->professional_number ?: null,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'profession' => 'psychology',
                'terms_accepted' => $this->terms_accepted,
            ];

            // Llamar directamente al servicio
            $result = app(AuthService::class)->register($registerData);
            
            // Store token in session
            session(['api_token' => $result['token']]);
            session(['user' => $result['user']]);
            
            // Simple auth simulation
            auth()->loginUsingId($result['user']->id);
            
            // Si el email no está verificado, redirigir a la página de verificación
            if (!$result['user']->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            
            return redirect()->route('psychologist.dashboard');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = 'Por favor, corrige los errores en el formulario.';
            
            // Set field errors
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, is_array($messages) ? $messages[0] : $messages);
            }
        } catch (\Exception $e) {
            Log::error('[REGISTER_FORM] Error inesperado', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->errorMessage = 'Error al registrarse: ' . ($e->getMessage() ?? 'Error inesperado');
        }
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
