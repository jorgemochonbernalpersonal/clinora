<?php

namespace App\Core\Authentication\DTOs;

use App\Shared\Enums\ProfessionType;
use App\Shared\Enums\SubscriptionPlan;

/**
 * Register User Data Transfer Object
 * 
 * Immutable DTO for user registration data
 */
readonly class RegisterUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
        public ?string $phone,
        public string $profession,
        public ?string $licenseNumber,
        public ?array $specialties,
        public bool $termsAccepted,
    ) {}

    /**
     * Create DTO from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            phone: $data['phone'] ?? null,
            profession: $data['profession'] ?? 'psychology',
            licenseNumber: $data['license_number'] ?? null,
            specialties: $data['specialties'] ?? null,
            termsAccepted: $data['terms_accepted'] ?? false,
        );
    }

    /**
     * Get user data array for creation
     */
    public function getUserData(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password, // Will be hashed in service
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'user_type' => 'professional',
            'language' => 'es',
            'timezone' => 'Europe/Madrid',
            'is_active' => true,
        ];
    }

    /**
     * Get professional data array for creation
     */
    public function getProfessionalData(): array
    {
        $professionType = $this->getProfessionType();

        return [
            'license_number' => $this->licenseNumber,
            'profession' => $this->profession,
            'profession_type' => $professionType,
            'specialties' => $this->specialties,
            'subscription_plan' => SubscriptionPlan::default()->value,
            'subscription_status' => 'active',
            'is_early_adopter' => now()->lte('2026-04-30 23:59:59'),
        ];
    }

    /**
     * Map profession string to ProfessionType enum
     */
    public function getProfessionType(): ProfessionType
    {
        return match($this->profession) {
            'psychology', 'psychologist' => ProfessionType::PSYCHOLOGIST,
            'therapy', 'therapist' => ProfessionType::THERAPIST,
            'nutrition', 'nutritionist' => ProfessionType::NUTRITIONIST,
            'psychiatry', 'psychiatrist' => ProfessionType::PSYCHIATRIST,
            default => ProfessionType::PSYCHOLOGIST,
        };
    }

    /**
     * Convert DTO to array (for logging, without password)
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'profession' => $this->profession,
            'license_number' => $this->licenseNumber,
            'specialties' => $this->specialties,
            'terms_accepted' => $this->termsAccepted,
        ];
    }

    /**
     * Validate terms acceptance
     */
    public function hasAcceptedTerms(): bool
    {
        return $this->termsAccepted === true;
    }
}
