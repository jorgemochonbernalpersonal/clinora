<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('old_password'),
        ]);
    }

    /** @test */
    public function it_can_request_password_reset(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/password/forgot', [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Email de recuperación enviado',
            ]);

        Mail::assertSent(\Illuminate\Auth\Notifications\ResetPassword::class);
    }

    /** @test */
    public function it_validates_email_on_password_reset_request(): void
    {
        $response = $this->postJson('/api/v1/auth/password/forgot', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_sends_reset_email(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/password/forgot', [
            'email' => $this->user->email,
        ]);

        Mail::assertSent(\Illuminate\Auth\Notifications\ResetPassword::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });
    }

    /** @test */
    public function it_can_reset_password_with_valid_token(): void
    {
        // Generar token válido
        $token = Password::broker()->createToken($this->user);

        $newPassword = 'new_secure_password123';

        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Contraseña restablecida exitosamente',
            ]);

        // Verificar que la contraseña fue actualizada
        $this->user->refresh();
        $this->assertTrue(Hash::check($newPassword, $this->user->password));
    }

    /** @test */
    public function it_rejects_invalid_reset_token(): void
    {
        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => 'invalid_token',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Token de restablecimiento inválido',
            ]);

        // Verificar que la contraseña NO cambió
        $this->assertTrue(Hash::check('old_password', $this->user->fresh()->password));
    }

    /** @test */
    public function it_rejects_expired_reset_token(): void
    {
        // Crear token y simular expiración (Laravel tokens expiran en 60 minutos por defecto)
        $token = Password::broker()->createToken($this->user);

        // Simular que pasó mucho tiempo (modificando timestamp en BD)
        \DB::table('password_reset_tokens')->where('email', $this->user->email)
            ->update(['created_at' => now()->subHours(2)]);

        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /** @test */
    public function it_validates_password_requirements(): void
    {
        $token = Password::broker()->createToken($this->user);

        // Contraseña demasiado corta
        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => '123', // Muy corta
            'password_confirmation' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_validates_password_confirmation_match(): void
    {
        $token = Password::broker()->createToken($this->user);

        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => 'new_password123',
            'password_confirmation' => 'different_password', // No coincide
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_deletes_token_after_successful_reset(): void
    {
        $token = Password::broker()->createToken($this->user);

        $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        // Intentar usar el mismo token de nuevo
        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => 'another_password',
            'password_confirmation' => 'another_password',
        ]);

        $response->assertStatus(422); // Token ya usado
    }

    /** @test */
    public function it_can_login_with_new_password_after_reset(): void
    {
        $token = Password::broker()->createToken($this->user);
        $newPassword = 'brand_new_password123';

        $this->postJson('/api/v1/auth/password/reset', [
            'email' => $this->user->email,
            'token' => $token,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        // Intentar login con nueva contraseña
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => $newPassword,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['token'],
            ]);
    }

    /** @test */
    public function it_rate_limits_password_reset_requests(): void
    {
        Mail::fake();

        // Hacer múltiples solicitudes
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/auth/password/forgot', [
                'email' => $this->user->email,
            ]);
        }

        // La 6ta solicitud debe ser rechazada por rate limiting
        $response->assertStatus(429); // Too Many Requests
    }
}
