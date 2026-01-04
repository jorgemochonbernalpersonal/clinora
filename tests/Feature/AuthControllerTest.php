<?php

namespace Tests\Feature;

use App\Core\Users\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_new_professional(): void
    {
        Role::firstOrCreate(['name' => 'professional']);

        $data = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'license_number' => '12345',
            'profession' => 'Psicólogo',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'email', 'first_name', 'last_name'],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
        ]);

        $user = User::where('email', 'new@example.com')->first();
        $this->assertNotNull($user->professional);
        $this->assertTrue($user->hasRole('professional'));
    }

    /** @test */
    public function it_validates_required_fields_on_registration(): void
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'first_name', 'last_name', 'profession']);
    }

    /** @test */
    public function it_validates_password_confirmation_on_registration(): void
    {
        $data = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'profession' => 'Psicólogo',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => ['id', 'email'],
                    'token',
                ],
            ]);

        $this->assertNotNull($response->json('data.token'));
    }

    /** @test */
    public function it_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_rejects_inactive_user_login(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_get_authenticated_user_info(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->getJson('/api/v1/auth/me', [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'email', 'professional'],
            ]);
    }

    /** @test */
    public function it_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ]);
    }
}

