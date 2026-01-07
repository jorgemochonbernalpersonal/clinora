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
        return DB::transaction(function () use ($data) {
            try {
                // Create user
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

                $user = User::create($userData);

                // Create professional profile
                // Map profession string to ProfessionType enum
                $professionType = match($data['profession'] ?? 'psychology') {
                    'psychology', 'psychologist' => \App\Shared\Enums\ProfessionType::PSYCHOLOGIST,
                    'therapy', 'therapist' => \App\Shared\Enums\ProfessionType::THERAPIST,
                    'nutrition', 'nutritionist' => \App\Shared\Enums\ProfessionType::NUTRITIONIST,
                    'psychiatry', 'psychiatrist' => \App\Shared\Enums\ProfessionType::PSYCHIATRIST,
                    default => \App\Shared\Enums\ProfessionType::PSYCHOLOGIST,
                };

                $professionalData = [
                    'license_number' => $data['license_number'] ?: null,
                    'profession' => $data['profession'],
                    'profession_type' => $professionType,  // ← FIX: Set profession_type enum
                    'specialties' => $data['specialties'] ?? null,
                    'subscription_plan' => \App\Shared\Enums\SubscriptionPlan::default()->value,
                    'subscription_status' => 'active',
                    // Mark as early adopter if registering before beta end date (April 30, 2026)
                    'is_early_adopter' => now()->lte('2026-04-30 23:59:59'),
                ];

                $professional = $user->professional()->create($professionalData);

                // Assign professional role
                $professionalRole = Role::firstOrCreate(['name' => 'professional']);
                $user->assignRole($professionalRole);

                // Send email verification
                try {
                    $user->sendEmailVerificationNotification();
                } catch (\Exception $e) {
                    Log::warning('[AUTH_SERVICE] No se pudo enviar email de verificación', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                    ]);
                    // No fallar el registro si el email falla
                }

                // Create token
                $token = $user->createToken('auth-token')->plainTextToken;

                $user->load(['professional', 'roles']);

                return [
                    'user' => $user,
                    'token' => $token,
                ];
            } catch (\Exception $e) {
                Log::error('[AUTH_SERVICE] Error durante el registro', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
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
