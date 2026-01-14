<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'two_factor_enabled' => false,
        ]);
    }

    /** @test */
    public function it_can_enable_two_factor_authentication(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/two-factor/enable');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'qr_code',
                    'secret',
                    'recovery_codes',
                ],
            ]);

        $this->user->refresh();
        $this->assertNotNull($this->user->two_factor_secret);
        $this->assertNotNull($this->user->two_factor_recovery_codes);
    }

    /** @test */
    public function it_requires_valid_code_to_confirm_2fa(): void
    {
        Sanctum::actingAs($this->user);

        // Habilitar 2FA (obtener secret)
        $enableResponse = $this->postJson('/api/v1/auth/two-factor/enable');
        $secret = $enableResponse->json('data.secret');

        // Generar código válido
        $google2fa = new Google2FA();
        $validCode = $google2fa->getCurrentOtp($secret);

        // Confirmar con código válido
        $response = $this->postJson('/api/v1/auth/two-factor/confirm', [
            'code' => $validCode,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Autenticación de dos factores activada exitosamente',
            ]);

        $this->user->refresh();
        $this->assertTrue($this->user->two_factor_enabled);
    }

    /** @test */
    public function it_rejects_invalid_2fa_confirmation_code(): void
    {
        Sanctum::actingAs($this->user);

        // Habilitar 2FA
        $this->postJson('/api/v1/auth/two-factor/enable');

        // Intentar confirmar con código inválido
        $response = $this->postJson('/api/v1/auth/two-factor/confirm', [
            'code' => '000000', // Código inválido
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Código de verificación inválido',
            ]);

        $this->user->refresh();
        $this->assertFalse($this->user->two_factor_enabled);
    }

    /** @test */
    public function it_can_disable_two_factor_authentication(): void
    {
        // Usuario con 2FA ya habilitado
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt('test_secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/two-factor/disable', [
            'password' => 'password123', // Confirmar con contraseña
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Autenticación de dos factores desactivada',
            ]);

        $this->user->refresh();
        $this->assertFalse($this->user->two_factor_enabled);
        $this->assertNull($this->user->two_factor_secret);
    }

    /** @test */
    public function it_requires_password_to_disable_2fa(): void
    {
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt('test_secret'),
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/two-factor/disable', [
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        $this->user->refresh();
        $this->assertTrue($this->user->two_factor_enabled);
    }

    /** @test */
    public function it_requires_2fa_code_on_login_when_enabled(): void
    {
        // Configurar usuario con 2FA habilitado
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($secret),
        ]);

        // Intentar login sin código 2FA
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'requires_2fa' => true,
            ]);

        // No debe retornar token aún
        $this->assertArrayNotHasKey('token', $response->json('data'));
    }

    /** @test */
    public function it_completes_login_with_valid_2fa_code(): void
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($secret),
        ]);

        // Login inicial
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $this->assertTrue($loginResponse->json('requires_2fa'));

        // Generar código válido
        $validCode = $google2fa->getCurrentOtp($secret);

        // Completar login con 2FA
        $response = $this->postJson('/api/v1/auth/two-factor/verify', [
            'email' => $this->user->email,
            'code' => $validCode,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['user', 'token'],
            ]);

        $this->assertNotNull($response->json('data.token'));
    }

    /** @test */
    public function it_rejects_invalid_2fa_code_on_login(): void
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($secret),
        ]);

        // Login inicial
        $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        // Intentar verificar con código inválido
        $response = $this->postJson('/api/v1/auth/two-factor/verify', [
            'email' => $this->user->email,
            'code' => '000000', // Inválido
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Código de autenticación inválido',
            ]);
    }

    /** @test */
    public function it_can_use_recovery_codes(): void
    {
        $recoveryCodes = ['ABC123', 'DEF456', 'GHI789'];
        
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt('test_secret'),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ]);

        // Login inicial
        $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        // Usar recovery code en lugar de código TOTP
        $response = $this->postJson('/api/v1/auth/two-factor/verify', [
            'email' => $this->user->email,
            'recovery_code' => 'ABC123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['user', 'token'],
            ]);

        // Verificar que el código fue marcado como usado
        $this->user->refresh();
        $updatedCodes = json_decode(decrypt($this->user->two_factor_recovery_codes), true);
        $this->assertNotContains('ABC123', $updatedCodes);
    }

    /** @test */
    public function it_generates_new_recovery_codes(): void
    {
        $this->user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt('test_secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['OLD1', 'OLD2'])),
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/two-factor/recovery-codes/regenerate');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['recovery_codes'],
            ]);

        $newCodes = $response->json('data.recovery_codes');
        $this->assertCount(8, $newCodes);
        $this->assertNotContains('OLD1', $newCodes);
    }

    /** @test */
    public function it_requires_authentication_to_manage_2fa(): void
    {
        $response = $this->postJson('/api/v1/auth/two-factor/enable');

        $response->assertStatus(401);
    }
}
