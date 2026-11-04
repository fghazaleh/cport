<?php

declare(strict_types=1);

namespace FG\Support\Math;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Static, typed wrappers around PHP's math functions plus common statistics.
 * Domain errors (empty input, division by zero, negative square root, ...)
 * throw InvalidArgumentException instead of returning NAN/INF or warning.
 */
final class Math
{
    private function __construct() {}

    public static function pi(): float
    {
        return M_PI;
    }

    public static function abs(int|float $value): int|float
    {
        return abs($value);
    }

    /**
     * Rounds fractions up.
     */
    public static function ceil(int|float $value): float
    {
        return ceil($value);
    }

    /**
     * Alias of ceil().
     */
    public static function roundUp(int|float $value): float
    {
        return self::ceil($value);
    }

    /**
     * Rounds fractions down.
     */
    public static function floor(int|float $value): float
    {
        return floor($value);
    }

    /**
     * Alias of floor().
     */
    public static function roundDown(int|float $value): float
    {
        return self::floor($value);
    }

    /**
     * @param int $mode one of the PHP_ROUND_* constants
     */
    public static function round(int|float $value, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): float
    {
        return round($value, $precision, $mode);
    }

    /**
     * Logarithm of $value in $base (natural logarithm by default).
     *
     * @throws InvalidArgumentException when $value <= 0, or $base <= 0 or $base == 1
     */
    public static function log(int|float $value, int|float $base = M_E): float
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('The logarithm is only defined for values greater than 0.');
        }
        if ($base <= 0 || $base == 1) {
            throw new InvalidArgumentException('The logarithm base must be greater than 0 and different from 1.');
        }

        return log($value, $base);
    }

    public static function log10(int|float $value): float
    {
        return self::log($value, 10);
    }

    public static function log2(int|float $value): float
    {
        return self::log($value, 2);
    }

    /**
     * @throws InvalidArgumentException for 0 raised to a negative power, or when the result is not a real number
     */
    public static function pow(int|float $base, int|float $exponent): int|float
    {
        if ($base == 0 && $exponent < 0) {
            throw new InvalidArgumentException('Zero cannot be raised to a negative power.');
        }

        $result = $base ** $exponent;
        if (\is_float($result) && is_nan($result)) {
            throw new InvalidArgumentException('The result is not a real number.');
        }

        return $result;
    }

    /**
     * @throws InvalidArgumentException when $value is negative
     */
    public static function sqrt(int|float $value): float
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Cannot take the square root of a negative number.');
        }

        return sqrt($value);
    }

    /**
     * @throws InvalidArgumentException when no values are given
     */
    public static function max(int|float ...$values): int|float
    {
        self::assertNotEmpty($values);

        return max($values);
    }

    /**
     * @throws InvalidArgumentException when no values are given
     */
    public static function min(int|float ...$values): int|float
    {
        self::assertNotEmpty($values);

        return min($values);
    }

    public static function sum(int|float ...$values): int|float
    {
        return array_sum($values);
    }

    /**
     * Arithmetic mean.
     *
     * @throws InvalidArgumentException when no values are given
     */
    public static function avg(int|float ...$values): float
    {
        self::assertNotEmpty($values);

        return array_sum($values) / \count($values);
    }

    /**
     * Median: the middle value, or the mean of the two middle values.
     *
     * @throws InvalidArgumentException when no values are given
     */
    public static function median(int|float ...$values): int|float
    {
        self::assertNotEmpty($values);
        sort($values);

        $count = \count($values);
        $middle = intdiv($count, 2);

        return $count % 2 === 1
            ? $values[$middle]
            : ($values[$middle - 1] + $values[$middle]) / 2;
    }

    /**
     * The most frequent value(s), sorted ascending. Several values are
     * returned when they share the highest frequency.
     *
     * @return list<int|float>
     * @throws InvalidArgumentException when no values are given
     */
    public static function mode(int|float ...$values): array
    {
        self::assertNotEmpty($values);

        $counts = [];
        foreach ($values as $value) {
            $key = (string) $value;
            $counts[$key] ??= ['count' => 0, 'value' => $value];
            $counts[$key]['count']++;
        }

        $highest = max(array_column($counts, 'count'));
        $modes = array_column(
            array_filter($counts, static fn(array $entry): bool => $entry['count'] === $highest),
            'value',
        );
        sort($modes);

        return $modes;
    }

    /**
     * Percentile with linear interpolation between ranks (inclusive method).
     *
     * @param float $percentile between 0 and 100
     * @param list<int|float> $values
     * @throws InvalidArgumentException when $values is empty or $percentile is out of range
     */
    public static function percentile(array $values, float $percentile): float
    {
        self::assertNotEmpty($values);
        if ($percentile < 0 || $percentile > 100) {
            throw new InvalidArgumentException('The percentile must be between 0 and 100.');
        }

        $values = array_values($values);
        sort($values);

        $rank = $percentile / 100 * (\count($values) - 1);
        $lower = (int) floor($rank);
        $upper = (int) ceil($rank);

        return $values[$lower] + ($values[$upper] - $values[$lower]) * ($rank - $lower);
    }

    /**
     * Limits $value to the range [$min, $max].
     *
     * @throws InvalidArgumentException when $min is greater than $max
     */
    public static function clamp(int|float $value, int|float $min, int|float $max): int|float
    {
        if ($min > $max) {
            throw new InvalidArgumentException('The minimum cannot be greater than the maximum.');
        }

        return min(max($value, $min), $max);
    }

    public static function between(int|float $value, int|float $min, int|float $max, bool $inclusive = true): bool
    {
        return $inclusive
            ? $value >= $min && $value <= $max
            : $value > $min && $value < $max;
    }

    /**
     * $part as a percentage of $total, e.g. percentage(1, 4) === 25.0.
     *
     * @throws InvalidArgumentException when $total is zero
     */
    public static function percentage(int|float $part, int|float $total, int $precision = 2): float
    {
        if ($total == 0) {
            throw new InvalidArgumentException('Cannot calculate a percentage of zero.');
        }

        return round($part / $total * 100, $precision);
    }

    /**
     * @param array<mixed> $values
     */
    private static function assertNotEmpty(array $values): void
    {
        if ($values === []) {
            throw new InvalidArgumentException('At least one value is required.');
        }
    }
}
