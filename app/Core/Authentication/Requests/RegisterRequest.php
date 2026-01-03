<?php

namespace App\Core\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // User data
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            
            // Professional data
            'license_number' => ['nullable', 'string', 'max:255', 'unique:professionals,license_number'],
            'profession' => ['required', 'in:psychology'], // ← SOLO PSICÓLOGOS
            'specialties' => ['nullable', 'array'],
            'specialties.*' => ['string', 'max:255'],
            
            // Terms acceptance
            'terms_accepted' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'first_name.required' => 'El nombre es obligatorio',
            'last_name.required' => 'Los apellidos son obligatorios',
            'license_number.unique' => 'Este número de colegiado ya está registrado',
            'profession.required' => 'La profesión es obligatoria',
            'profession.in' => 'Actualmente solo aceptamos psicólogos',
            'terms_accepted.accepted' => 'Debes aceptar los términos y condiciones',
        ];
    }
}
