<?php

namespace App\Core\Authentication\Controllers;

use App\Core\Authentication\DTOs\LoginCredentialsDTO;
use App\Core\Authentication\DTOs\RegisterUserDTO;
use App\Core\Authentication\Requests\LoginRequest;
use App\Core\Authentication\Requests\RegisterRequest;
use App\Core\Authentication\Resources\AuthenticationResource;
use App\Core\Authentication\Resources\UserResource;
use App\Core\Authentication\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $this->logInfo('Intento de login', [
                'email' => $request->input('email'),
            ]);

            $credentials = LoginCredentialsDTO::fromArray($request->validated());
            $result = $this->authService->login($credentials);

            $this->logUserAction('Login exitoso', [
                'user_id' => $result['user']->id,
                'email' => $result['user']->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => new AuthenticationResource($result),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->logWarning('Intento de login fallido', [
                'email' => $request->input('email'),
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError('Error inesperado en login', $e, [
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
            ], 500);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $this->logInfo('Inicio del proceso de registro', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);

            $dto = RegisterUserDTO::fromArray($request->validated());
            $result = $this->authService->register($dto);

            $this->logUserAction('Registro exitoso', [
                'user_id' => $result['user']->id,
                'email' => $result['user']->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro exitoso. ¡Bienvenido a Clinora!',
                'data' => new AuthenticationResource($result),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->logWarning('Error de validación en registro', [
                'email' => $request->input('email'),
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->logError('Error de base de datos en registro', $e, [
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cuenta',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        } catch (\Exception $e) {
            $this->logError('Error en registro de usuario', $e, [
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en el registro',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        }
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user()->load(['professional', 'roles', 'permissions'])),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->logUserAction('Logout', [
            'user_id' => $request->user()->id,
        ]);

        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente',
        ]);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $this->logUserAction('Logout de todos los dispositivos', [
            'user_id' => $request->user()->id,
        ]);

        $this->authService->logoutAllDevices($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada en todos los dispositivos',
        ]);
    }
}
