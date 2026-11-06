<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Immutable number: every operation returns a new instance.
 */
final readonly class Number implements \JsonSerializable, \Stringable
{
    private function __construct(private int|float $value) {}

    /**
     * @throws InvalidArgumentException when a string is not numeric
     */
    public static function of(int|float|string|\Stringable $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if ($value instanceof \Stringable) {
            $value = (string) $value;
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

    public function plus(int|float|self $number): self
    {
        return new self($this->value + self::raw($number));
    }

    public function minus(int|float|self $number): self
    {
        return new self($this->value - self::raw($number));
    }

    public function multiply(int|float|self $number): self
    {
        return new self($this->value * self::raw($number));
    }

    /**
     * @throws InvalidArgumentException when dividing by zero
     */
    public function divide(int|float|self $number): self
    {
        $divisor = self::raw($number);

        if ($divisor == 0) {
            throw new InvalidArgumentException('Cannot divide by zero.');
        }

        return new self($this->value / $divisor);
    }

    /**
     * @throws InvalidArgumentException when the divisor is zero
     */
    public function modulo(int|float|self $number): self
    {
        $divisor = self::raw($number);

        if ($divisor == 0) {
            throw new InvalidArgumentException('Cannot calculate the modulo of zero.');
        }

        return new self(
            \is_int($this->value) && \is_int($divisor) ? $this->value % $divisor : Math::fmod($this->value, $divisor),
        );
    }

    public function power(int|float|self $exponent): self
    {
        return new self(Math::pow($this->value, self::raw($exponent)));
    }

    public function squareRoot(): self
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

    /**
     * Limits the number to the range [$min, $max].
     *
     * @throws InvalidArgumentException when $min is greater than $max
     */
    public function clamp(int|float|self $min, int|float|self $max): self
    {
        $min = self::raw($min);
        $max = self::raw($max);

        if ($min > $max) {
            throw new InvalidArgumentException('The minimum cannot be greater than the maximum.');
        }

        return new self(min(max($this->value, $min), $max));
    }

    /**
     * Compares with another number: -1, 0 or 1.
     */
    public function compare(int|float|self $number): int
    {
        return $this->value <=> self::raw($number);
    }

    /**
     * @param float $epsilon allowed difference, useful for floats
     */
    public function equals(int|float|self $number, float $epsilon = 0.0): bool
    {
        return abs($this->value - self::raw($number)) <= $epsilon;
    }

    public function isBetween(int|float|self $min, int|float|self $max, bool $inclusive = true): bool
    {
        $min = self::raw($min);
        $max = self::raw($max);

        return $inclusive
            ? $this->value >= $min && $this->value <= $max
            : $this->value > $min && $this->value < $max;
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
     * What percentage this number is of $total, e.g. 1 of 4 is 25.
     *
     * @throws InvalidArgumentException when $total is zero
     */
    public function percentOf(int|float|self $total, int $precision = 2): self
    {
        $total = self::raw($total);

        if ($total == 0) {
            throw new InvalidArgumentException('Cannot calculate a percentage of zero.');
        }

        return new self(round($this->value / $total * 100, $precision));
    }

    /**
     * Formats like number_format(), e.g. 1234.5 => "1,234.50".
     */
    public function format(int $decimals = 0, string $decimalSeparator = '.', string $thousandsSeparator = ','): string
    {
        return Math::format($this->value, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    /**
     * 1 => "1st", 2 => "2nd", 11 => "11th", 23 => "23rd".
     *
     * @throws InvalidArgumentException when the number is not whole
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
