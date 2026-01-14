<?php

namespace App\Core\Authentication\DTOs;

/**
 * Login Credentials Data Transfer Object
 * 
 * Immutable DTO for user login credentials
 */
readonly class LoginCredentialsDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
        public ?string $twoFactorCode = null,
    ) {}

    /**
     * Create DTO from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: $data['remember'] ?? false,
            twoFactorCode: $data['two_factor_code'] ?? null,
        );
    }

    /**
     * Convert DTO to array (for logging, without password)
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'remember' => $this->remember,
            'has_two_factor_code' => $this->twoFactorCode !== null,
        ];
    }

    /**
     * Get credentials array for authentication
     */
    public function getCredentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
