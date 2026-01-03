<?php

namespace App\Core\Authentication\Controllers;

use App\Core\Authentication\Requests\LoginRequest;
use App\Core\Authentication\Requests\RegisterRequest;
use App\Core\Authentication\Resources\AuthenticationResource;
use App\Core\Authentication\Resources\UserResource;
use App\Core\Authentication\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            $result = $this->authService->login($request->validated());

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
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        Log::info('[REGISTER] Inicio del proceso de registro', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);

        try {
            // Log datos recibidos (sin password)
            $logData = $request->validated();
            unset($logData['password'], $logData['password_confirmation']);

            Log::info('[REGISTER] Datos validados recibidos', [
                'data' => $logData,
                'has_password' => $request->has('password'),
                'has_password_confirmation' => $request->has('password_confirmation'),
            ]);

            Log::info('[REGISTER] Llamando a AuthService::register');
            $result = $this->authService->register($request->validated());

            Log::info('[REGISTER] Usuario creado exitosamente', [
                'user_id' => $result['user']->id,
                'email' => $result['user']->email,
                'has_professional' => $result['user']->professional !== null,
                'professional_id' => $result['user']->professional?->id,
                'has_token' => !empty($result['token']),
            ]);

            $this->logUserAction('Registro exitoso', [
                'user_id' => $result['user']->id,
                'email' => $result['user']->email,
            ]);

            Log::info('[REGISTER] Preparando respuesta exitosa');
            return response()->json([
                'success' => true,
                'message' => 'Registro exitoso. ¡Bienvenido a Clinora!',
                'data' => new AuthenticationResource($result),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('[REGISTER] Error de validación', [
                'email' => $request->input('email'),
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('[REGISTER] Error de base de datos', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? [],
                'trace' => $e->getTraceAsString(),
            ]);

            $this->logError('Error de base de datos en registro', $e, [
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cuenta',
                'error' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
            ], 500);
        } catch (\Exception $e) {
            Log::error('[REGISTER] Error general en registro', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

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
