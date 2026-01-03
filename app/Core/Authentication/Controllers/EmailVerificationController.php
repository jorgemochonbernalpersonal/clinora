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
        $user = \App\Models\User::findOrFail($id);

        // Verify the hash matches
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
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

        // Redirect to dashboard with success message
        return redirect()->route('dashboard')->with('success', '¡Email verificado exitosamente! Bienvenido a Clinora.');
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
