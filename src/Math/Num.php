<?php

declare(strict_types=1);

namespace FG\Support\Math;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Immutable number value object: every operation returns a new instance.
 */
final readonly class Num implements \Stringable, \JsonSerializable
{
    private function __construct(private int|float $value) {}

    /**
     * @throws InvalidArgumentException when a string is not numeric
     */
    public static function of(int|float|string|self $value): self
    {
        if ($value instanceof self) {
            return $value;
        }
        if (\is_string($value)) {
            if (!is_numeric($value)) {
                throw new InvalidArgumentException(\sprintf('"%s" is not a numeric string.', $value));
            }
            $value += 0;
        }

        return new self($value);
    }

    public function value(): int|float
    {
        return $this->value;
    }

    public function toInt(): int
    {
        return (int) $this->value;
    }

    public function toFloat(): float
    {
        return (float) $this->value;
    }

    public function add(int|float|self $other): self
    {
        return new self($this->value + self::raw($other));
    }

    public function sub(int|float|self $other): self
    {
        return new self($this->value - self::raw($other));
    }

    public function mul(int|float|self $other): self
    {
        return new self($this->value * self::raw($other));
    }

    /**
     * @throws InvalidArgumentException when dividing by zero
     */
    public function div(int|float|self $other): self
    {
        $divisor = self::raw($other);
        if ($divisor == 0) {
            throw new InvalidArgumentException('Cannot divide by zero.');
        }

        return new self($this->value / $divisor);
    }

    /**
     * @throws InvalidArgumentException when the divisor is zero
     */
    public function mod(int|float|self $other): self
    {
        $divisor = self::raw($other);
        if ($divisor == 0) {
            throw new InvalidArgumentException('Cannot calculate the modulo of zero.');
        }

        return new self(
            \is_int($this->value) && \is_int($divisor) ? $this->value % $divisor : fmod($this->value, $divisor),
        );
    }

    public function pow(int|float|self $exponent): self
    {
        return new self(Math::pow($this->value, self::raw($exponent)));
    }

    public function sqrt(): self
    {
        return new self(Math::sqrt($this->value));
    }

    public function abs(): self
    {
        return new self(Math::abs($this->value));
    }

    public function round(int $precision = 0, int $mode = PHP_ROUND_HALF_UP): self
    {
        return new self(Math::round($this->value, $precision, $mode));
    }

    public function ceil(): self
    {
        return new self(Math::ceil($this->value));
    }

    public function floor(): self
    {
        return new self(Math::floor($this->value));
    }

    public function clamp(int|float|self $min, int|float|self $max): self
    {
        return new self(Math::clamp($this->value, self::raw($min), self::raw($max)));
    }

    public function isBetween(int|float|self $min, int|float|self $max, bool $inclusive = true): bool
    {
        return Math::between($this->value, self::raw($min), self::raw($max), $inclusive);
    }

    /**
     * The share this number represents of $total, as a percentage.
     */
    public function percentOf(int|float|self $total, int $precision = 2): float
    {
        return Math::percentage($this->value, self::raw($total), $precision);
    }

    /**
     * Compares with another number: -1, 0 or 1.
     */
    public function compare(int|float|self $other): int
    {
        return $this->value <=> self::raw($other);
    }

    /**
     * @param float $epsilon allowed difference, useful for floats
     */
    public function equals(int|float|self $other, float $epsilon = 0.0): bool
    {
        return abs($this->value - self::raw($other)) <= $epsilon;
    }

    public function isZero(): bool
    {
        return $this->value == 0;
    }

    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    public function isNegative(): bool
    {
        return $this->value < 0;
    }

    public function isEven(): bool
    {
        return $this->isWhole() && (int) $this->value % 2 === 0;
    }

    public function isOdd(): bool
    {
        return $this->isWhole() && (int) $this->value % 2 !== 0;
    }

    public function isWhole(): bool
    {
        return \is_int($this->value) || $this->value == floor($this->value);
    }

    /**
     * Formats like number_format(), e.g. 1234.5 => "1,234.50".
     */
    public function format(int $decimals = 0, string $decimalSeparator = '.', string $thousandsSeparator = ','): string
    {
        return number_format($this->value, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    /**
     * 1 => "1st", 2 => "2nd", 11 => "11th", 23 => "23rd".
     *
     * @throws InvalidArgumentException when the number is not a whole number
     */
    public function toOrdinal(): string
    {
        if (!$this->isWhole()) {
            throw new InvalidArgumentException('Only whole numbers have an ordinal form.');
        }

        $number = (int) $this->value;
        $lastTwo = abs($number) % 100;
        $suffix = ($lastTwo >= 11 && $lastTwo <= 13)
            ? 'th'
            : match (abs($number) % 10) {
                1 => 'st',
                2 => 'nd',
                3 => 'rd',
                default => 'th',
            };

        return $number . $suffix;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): int|float
    {
        return $this->value;
    }

    private static function raw(int|float|self $number): int|float
    {
        return $number instanceof self ? $number->value : $number;
    }
}
