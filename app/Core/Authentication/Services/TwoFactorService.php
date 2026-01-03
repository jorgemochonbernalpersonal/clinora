<?php

namespace App\Core\Authentication\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TwoFactorService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key for 2FA
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR code URL for Google Authenticator
     */
    public function getQRCodeUrl(User $user, string $secret): string
    {
        $appName = config('app.name', 'Clinora');
        
        return $this->google2fa->getQRCodeUrl(
            $appName,
            $user->email,
            $secret
        );
    }

    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        
        for ($i = 0; $i < 8; $i++) {
            $codes[] = Str::random(10);
        }
        
        return $codes;
    }

    /**
     * Enable 2FA for a user
     */
    public function enable(User $user, string $code): bool
    {
        // Verify the code before enabling
        if (!$this->verify($user->two_factor_secret, $code)) {
            return false;
        }

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        // Enable 2FA
        $user->update([
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => json_encode($recoveryCodes),
        ]);

        return true;
    }

    /**
     * Disable 2FA for a user
     */
    public function disable(User $user): void
    {
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ]);
    }

    /**
     * Verify a 2FA code
     */
    public function verify(?string $secret, string $code): bool
    {
        if (!$secret) {
            return false;
        }

        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Verify or use a recovery code
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $recoveryCodes = json_decode($user->two_factor_recovery_codes, true) ?? [];

        if (in_array($code, $recoveryCodes)) {
            // Remove the used code
            $remainingCodes = array_values(array_diff($recoveryCodes, [$code]));
            
            $user->update([
                'two_factor_recovery_codes' => json_encode($remainingCodes),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Check if user has 2FA enabled
     */
    public function isEnabled(User $user): bool
    {
        return $user->two_factor_enabled && $user->two_factor_secret;
    }
}
