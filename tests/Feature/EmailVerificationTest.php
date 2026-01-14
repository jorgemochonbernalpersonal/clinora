<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_verification_email_on_registration(): void
    {
        Mail::fake();

        $data = [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'profession' => 'Psicólogo',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(201);

        Mail::assertSent(\Illuminate\Auth\Notifications\VerifyEmail::class);
    }

    /** @test */
    public function it_can_verify_email_with_valid_link(): void
    {
        Event::fake([Verified::class]);

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        Event::assertDispatched(Verified::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function it_rejects_verification_with_invalid_signature(): void
    {
        $user = User::factory()->unverified()->create();

        // URL sin firma válida
        $invalidUrl = route('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $response = $this->get($invalidUrl);

        $response->assertStatus(403); // Forbidden

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    /** @test */
    public function it_rejects_verification_with_wrong_hash(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1('wrong@email.com'), // Hash incorrecto
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(403); // Forbidden

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    /** @test */
    public function it_can_resend_verification_email(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/v1/auth/email/resend');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Email de verificación enviado',
            ]);

        Mail::assertSent(\Illuminate\Auth\Notifications\VerifyEmail::class);
    }

    /** @test */
    public function it_does_not_resend_if_already_verified(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/v1/auth/email/resend');

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'El email ya ha sido verificado',
            ]);

        Mail::assertNothingSent();
    }

    /** @test */
    public function it_redirects_verified_users_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_prevents_access_to_protected_routes_without_verification(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        // Ruta que requiere verificación
        $response = $this->getJson('/api/v1/appointments');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Debes verificar tu email antes de continuar',
            ]);
    }

    /** @test */
    public function it_allows_access_to_protected_routes_after_verification(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/v1/appointments');

        $response->assertSuccessful();
    }

    /** @test */
    public function it_requires_authentication_to_resend_verification(): void
    {
        $response = $this->postJson('/api/v1/auth/email/resend');

        $response->assertStatus(401);
    }
}
