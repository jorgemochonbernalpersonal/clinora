<?php

namespace App\Core\Authentication\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Send verification email
     * 
     * @group Authentication
     */
    public function sendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Tu email ya está verificado',
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Te hemos enviado un nuevo email de verificación',
        ]);
    }

    /**
     * Verify email (API)
     * 
     * @group Authentication
     */
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Tu email ya estaba verificado',
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'success' => true,
            'message' => '¡Email verificado exitosamente!',
        ]);
    }

    /**
     * Verify email (Web) - Redirects to dashboard after verification
     */
    public function verifyWeb(Request $request, $id, $hash)
    {
        try {
            $user = \App\Models\User::findOrFail($id);

            // Verify the hash matches
            $expectedHash = sha1($user->getEmailForVerification());
            if (!hash_equals((string) $hash, $expectedHash)) {
                \Log::warning('[EMAIL_VERIFICATION] Hash mismatch', [
                    'user_id' => $id,
                    'email' => $user->email,
                ]);
                abort(403, 'El enlace de verificación no es válido.');
            }

            // Check if already verified
            if ($user->hasVerifiedEmail()) {
                // If already verified, authenticate and redirect to dashboard
                auth()->login($user);
                return redirect()->route('dashboard')->with('success', 'Tu email ya estaba verificado.');
            }

            // Mark email as verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            // Authenticate the user
            auth()->login($user);

            // Get or create API token for the user
            $token = $user->createToken('auth-token')->plainTextToken;
            session(['api_token' => $token]);
            session(['user' => $user]);

            // Set localStorage flag to notify other tabs
            $redirectScript = "
                <script>
                    localStorage.setItem('email_verified', 'true');
                    setTimeout(() => {
                        window.location.href = '" . route('dashboard') . "';
                    }, 500);
                </script>
            ";

            // Return HTML with redirect script
            return response($redirectScript . '
                <div style="display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;">
                    <div style="text-align:center;">
                        <h2 style="color:#10b981;">✅ Email verificado exitosamente</h2>
                        <p style="color:#6b7280;">Redirigiendo al dashboard...</p>
                    </div>
                </div>
            ');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('[EMAIL_VERIFICATION] User not found', ['user_id' => $id]);
            abort(404, 'Usuario no encontrado.');
        } catch (\Exception $e) {
            \Log::error('[EMAIL_VERIFICATION] Verification failed', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->route('login')->with('error', 'Hubo un error al verificar tu email. Por favor, intenta de nuevo o contacta con soporte.');
        }
    }

    /**
     * Check verification status
     * 
     * @group Authentication
     */
    public function checkStatus(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'email_verified' => $request->user()->hasVerifiedEmail(),
                'email_verified_at' => $request->user()->email_verified_at?->toIso8601String(),
            ],
        ]);
    }
}
