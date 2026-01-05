<?php

namespace App\Core\Authentication\Services;

use App\Models\User;
use App\Shared\Interfaces\ServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthService implements ServiceInterface
{
    /**
     * Authenticate user and generate token
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta está desactivada. Contacta con soporte.'],
            ]);
        }

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user->load(['professional', 'roles', 'permissions']),
            'token' => $token,
        ];
    }

    /**
     * Register a new professional user
     */
    public function register(array $data): array
    {
        Log::info('[AUTH_SERVICE] Iniciando registro de usuario', [
            'email' => $data['email'] ?? 'N/A',
            'has_all_required_fields' => isset($data['email'], $data['password'], $data['first_name'], $data['last_name'], $data['license_number'], $data['profession']),
        ]);

        return DB::transaction(function () use ($data) {
            Log::info('[AUTH_SERVICE] Transacción iniciada');
            
            try {
                // Create user
                Log::info('[AUTH_SERVICE] Creando usuario', [
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                ]);

                $userData = [
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'] ?? null,
                    'user_type' => 'professional',
                    'language' => 'es',
                    'timezone' => 'Europe/Madrid',
                    'is_active' => true,
                ];

                Log::debug('[AUTH_SERVICE] Datos del usuario a crear', [
                    'user_data' => array_merge($userData, ['password' => '***HIDDEN***']),
                ]);

                $user = User::create($userData);

                Log::info('[AUTH_SERVICE] Usuario creado', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                // Create professional profile
                Log::info('[AUTH_SERVICE] Creando perfil profesional', [
                    'user_id' => $user->id,
                    'license_number' => $data['license_number'] ?? 'N/A',
                    'profession' => $data['profession'],
                ]);

                $professionalData = [
                    'license_number' => $data['license_number'] ?: null,
                    'profession' => $data['profession'],
                    'specialties' => $data['specialties'] ?? null,
                    'subscription_plan' => \App\Shared\Enums\SubscriptionPlan::default()->value, // Plan gratis por defecto
                    'subscription_status' => 'active',
                    // Mark as early adopter if registering before beta end date (April 30, 2026)
                    'is_early_adopter' => now()->lte('2026-04-30 23:59:59'),
                ];

                Log::debug('[AUTH_SERVICE] Datos del profesional a crear', [
                    'professional_data' => $professionalData,
                ]);

                $professional = $user->professional()->create($professionalData);

                Log::info('[AUTH_SERVICE] Perfil profesional creado', [
                    'professional_id' => $professional->id,
                    'user_id' => $user->id,
                    'license_number' => $professional->license_number,
                ]);

                // Assign professional role
                Log::info('[AUTH_SERVICE] Asignando rol profesional');
                $professionalRole = Role::firstOrCreate(['name' => 'professional']);
                Log::debug('[AUTH_SERVICE] Rol obtenido/creado', [
                    'role_id' => $professionalRole->id,
                    'role_name' => $professionalRole->name,
                ]);

                $user->assignRole($professionalRole);
                Log::info('[AUTH_SERVICE] Rol asignado al usuario', [
                    'user_id' => $user->id,
                    'role' => $professionalRole->name,
                ]);

                // Send email verification
                Log::info('[AUTH_SERVICE] Enviando email de verificación');
                try {
                    $user->sendEmailVerificationNotification();
                    Log::info('[AUTH_SERVICE] Email de verificación enviado exitosamente');
                } catch (\Exception $e) {
                    Log::warning('[AUTH_SERVICE] No se pudo enviar email de verificación', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                    ]);
                    // No fallar el registro si el email falla
                }

                // Create token
                Log::info('[AUTH_SERVICE] Creando token de autenticación');
                $token = $user->createToken('auth-token')->plainTextToken;
                Log::info('[AUTH_SERVICE] Token creado exitosamente', [
                    'token_length' => strlen($token),
                ]);

                Log::info('[AUTH_SERVICE] Cargando relaciones del usuario');
                $user->load(['professional', 'roles']);

                Log::info('[AUTH_SERVICE] Registro completado exitosamente', [
                    'user_id' => $user->id,
                    'professional_id' => $user->professional->id,
                    'has_roles' => $user->roles->isNotEmpty(),
                ]);

                return [
                    'user' => $user,
                    'token' => $token,
                ];
            } catch (\Exception $e) {
                Log::error('[AUTH_SERVICE] Error dentro de la transacción', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }
        });
    }

    /**
     * Logout user (revoke current token)
     */
    public function logout(User $user): void
    {
        // Revoke current token only
        $user->currentAccessToken()->delete();
    }

    /**
     * Logout from all devices (revoke all tokens)
     */
    public function logoutAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }
}
