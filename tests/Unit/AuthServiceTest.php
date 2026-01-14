<?php

namespace Tests\Unit;

use App\Core\Authentication\Services\AuthService;
use App\Core\Authentication\DTOs\LoginCredentialsDTO;
use App\Core\Authentication\DTOs\RegisterUserDTO;
use App\Core\Users\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AuthService::class);
    }

    /** @test */
    public function it_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $result = $this->service->login(new LoginCredentialsDTO(
            email: 'test@example.com',
            password: 'password123',
        ));

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertNotNull($result['token']);
    }

    /** @test */
    public function it_throws_exception_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->expectException(ValidationException::class);

        $this->service->login(new LoginCredentialsDTO(
            email: 'test@example.com',
            password: 'wrongpassword',
        ));
    }

    /** @test */
    public function it_throws_exception_when_user_is_inactive(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $this->expectException(ValidationException::class);

        $this->service->login(new LoginCredentialsDTO(
            email: 'test@example.com',
            password: 'password123',
        ));
    }

    /** @test */
    public function it_updates_last_login_on_successful_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'last_login_at' => null,
        ]);

        $this->service->login(new LoginCredentialsDTO(
            email: 'test@example.com',
            password: 'password123',
        ));

        $user->refresh();
        $this->assertNotNull($user->last_login_at);
    }

    /** @test */
    public function it_can_register_a_new_professional(): void
    {
        Role::firstOrCreate(['name' => 'professional']);

        $data = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'license_number' => '12345',
            'profession' => 'Psicólogo',
        ];

        $result = $this->service->register(RegisterUserDTO::fromArray($data));

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('new@example.com', $result['user']->email);
        $this->assertNotNull($result['user']->professional);
        $this->assertEquals('Psicólogo', $result['user']->professional->profession);
        $this->assertTrue($result['user']->hasRole('professional'));
    }

    /** @test */
    public function it_creates_professional_profile_on_registration(): void
    {
        Role::firstOrCreate(['name' => 'professional']);

        $data = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'license_number' => '12345',
            'profession' => 'Psicólogo',
        ];

        $result = $this->service->register(RegisterUserDTO::fromArray($data));

        $this->assertNotNull($result['user']->professional);
        $this->assertEquals('12345', $result['user']->professional->license_number);
    }

    /** @test */
    public function it_assigns_professional_role_on_registration(): void
    {
        $role = Role::firstOrCreate(['name' => 'professional']);

        $data = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'license_number' => '12345',
            'profession' => 'Psicólogo',
        ];

        $result = $this->service->register(RegisterUserDTO::fromArray($data));

        $this->assertTrue($result['user']->hasRole('professional'));
    }

    /** @test */
    public function it_can_logout_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->service->logout($user);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    /** @test */
    public function it_can_logout_from_all_devices(): void
    {
        $user = User::factory()->create();
        $user->createToken('token1');
        $user->createToken('token2');
        $user->createToken('token3');

        $this->service->logoutAllDevices($user);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }
}

