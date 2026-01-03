<?php

namespace App\Core\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'token.required' => 'El token es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
