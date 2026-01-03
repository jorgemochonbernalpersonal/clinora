<?php

namespace App\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Money Value Object
 * 
 * Immutable representation of monetary values
 * Handles currency and decimal precision correctly
 */
final class Money implements JsonSerializable
{
    private const PRECISION = 2;

    public function __construct(
        public readonly float $amount,
        public readonly string $currency = 'EUR'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Money amount cannot be negative');
        }
    }

    /**
     * Create from cents/minor units
     */
    public static function fromCents(int $cents, string $currency = 'EUR'): self
    {
        return new self($cents / 100, $currency);
    }

    /**
     * Get amount in cents/minor units
     */
    public function toCents(): int
    {
        return (int) round($this->amount * 100);
    }

    /**
     * Add money
     */
    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    /**
     * Subtract money
     */
    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount - $other->amount, $this->currency);
    }

    /**
     * Multiply by factor
     */
    public function multiply(float $factor): self
    {
        return new self($this->amount * $factor, $this->currency);
    }

    /**
     * Format for display
     */
    public function formatted(): string
    {
        return number_format($this->amount, self::PRECISION, ',', '.') . ' ' . $this->currency;
    }

    /**
     * Check if same currency
     */
    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot operate on different currencies');
        }
    }

    /**
     * JSON serialization
     */
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'formatted' => $this->formatted(),
        ];
    }

    /**
     * String representation
     */
    public function __toString(): string
    {
        return $this->formatted();
    }
}
