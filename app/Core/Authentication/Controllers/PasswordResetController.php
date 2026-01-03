<?php

namespace App\Core\Authentication\Controllers;

use App\Core\Authentication\Requests\ForgotPasswordRequest;
use App\Core\Authentication\Requests\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link
     * 
     * @group Authentication
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Te hemos enviado un enlace para restablecer tu contraseña',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No pudimos enviar el enlace. Inténtalo de nuevo.',
        ], 500);
    }

    /**
     * Reset password
     * 
     * @group Authentication
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'password_changed_at' => now(),
                ])->save();

                // Revoke all tokens for security
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Contraseña restablecida exitosamente. Por favor, inicia sesión.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status),
        ], 400);
    }
}
