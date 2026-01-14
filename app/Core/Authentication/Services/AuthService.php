<?php

namespace App\Core\Authentication\Services;

use App\Core\Authentication\DTOs\LoginCredentialsDTO;
use App\Core\Authentication\DTOs\RegisterUserDTO;
use App\Core\Authentication\Repositories\ProfessionalRepository;
use App\Core\Authentication\Repositories\UserRepository;
use App\Models\User;
use App\Shared\Interfaces\ServiceInterface;
use App\Shared\Traits\Loggable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthService implements ServiceInterface
{
    use Loggable;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    /**
     * Authenticate user and generate token
     */
    public function login(LoginCredentialsDTO $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials->email);

        if (!$user || !Hash::check($credentials->password, $user->password)) {
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
        $this->userRepository->updateLastLogin($user, request()->ip());

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
    public function register(RegisterUserDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            try {
                // Prepare user data with hashed password
                $userData = $dto->getUserData();
                $userData['password'] = Hash::make($dto->password);

                // Create user
                $user = $this->userRepository->create($userData);

                // Create professional profile
                $professionalData = $dto->getProfessionalData();
                $professional = $this->professionalRepository->createForUser(
                    $user->id,
                    $professionalData
                );

                // Assign professional role
                $professionalRole = Role::firstOrCreate(['name' => 'professional']);
                $user->assignRole($professionalRole);

                // Send email verification (non-blocking)
                $this->sendVerificationEmail($user);

                // Create token
                $token = $user->createToken('auth-token')->plainTextToken;

                $user->load(['professional', 'roles']);

                return [
                    'user' => $user,
                    'token' => $token,
                ];
            } catch (\Exception $e) {
                $this->logError('Error durante el registro', $e, [
                    'email' => $dto->email,
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
        $user->currentAccessToken()?->delete();
    }

    /**
     * Logout from all devices (revoke all tokens)
     */
    public function logoutAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Send verification email (non-blocking)
     */
    private function sendVerificationEmail(User $user): void
    {
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            $this->logWarning('No se pudo enviar email de verificación', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
        }
    }
}
