<?php

namespace App\Shared\ValueObjects;

use JsonSerializable;

/**
 * Address Value Object
 * 
 * Immutable representation of a physical address
 */
final class Address implements JsonSerializable
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $state,
        public readonly string $postalCode,
        public readonly string $country,
        public readonly ?string $additionalInfo = null
    ) {
    }

    /**
     * Create from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            street: $data['street'] ?? '',
            city: $data['city'] ?? '',
            state: $data['state'] ?? '',
            postalCode: $data['postal_code'] ?? '',
            country: $data['country'] ?? 'España',
            additionalInfo: $data['additional_info'] ?? null
        );
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'additional_info' => $this->additionalInfo,
        ];
    }

    /**
     * Get formatted address string
     */
    public function formatted(): string
    {
        $parts = array_filter([
            $this->street,
            $this->additionalInfo,
            "{$this->postalCode} {$this->city}",
            $this->state,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * JSON serialization
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * String representation
     */
    public function __toString(): string
    {
        return $this->formatted();
    }
}
