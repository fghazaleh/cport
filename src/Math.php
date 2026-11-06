<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Static, typed wrappers around PHP's math and random functions.
 *
 * Every method takes the subject first, never changes its arguments and
 * never returns false on failure. See docs/FUNCTIONS.md for the full list.
 */
final class Math
{
    private function __construct() {}

    /**
     * Wraps abs.
     */
    public static function abs(int|float $number): int|float
    {
        return abs($number);
    }

    /**
     * Wraps acos (throws instead of NAN).
     */
    public static function acos(int|float $number): float
    {
        self::assertThat($number >= -1 && $number <= 1, 'acos() is only defined for numbers between -1 and 1.');

        return acos($number);
    }

    /**
     * Wraps acosh (throws instead of NAN).
     */
    public static function acosh(int|float $number): float
    {
        self::assertThat($number >= 1, 'acosh() is only defined for numbers greater than or equal to 1.');

        return acosh($number);
    }

    /**
     * Wraps asin (throws instead of NAN).
     */
    public static function asin(int|float $number): float
    {
        self::assertThat($number >= -1 && $number <= 1, 'asin() is only defined for numbers between -1 and 1.');

        return asin($number);
    }

    /**
     * Wraps asinh.
     */
    public static function asinh(int|float $number): float
    {
        return asinh($number);
    }

    /**
     * Wraps atan.
     */
    public static function atan(int|float $number): float
    {
        return atan($number);
    }

    /**
     * Wraps atan2.
     */
    public static function atan2(int|float $y, int|float $x): float
    {
        return atan2($y, $x);
    }

    /**
     * Wraps atanh (throws instead of NAN/INF).
     */
    public static function atanh(int|float $number): float
    {
        self::assertThat($number > -1 && $number < 1, 'atanh() is only defined for numbers between -1 and 1 (exclusive).');

        return atanh($number);
    }

    /**
     * Wraps base_convert (throws on invalid digits; loses precision above PHP_INT_MAX).
     */
    public static function baseConvert(string $number, int $fromBase, int $toBase): string
    {
        if ($fromBase < 2 || $fromBase > 36 || $toBase < 2 || $toBase > 36) {
            throw new \ValueError('The bases must be between 2 and 36.');
        }
        self::assertDigits($number, $fromBase);

        return base_convert($number, $fromBase, $toBase);
    }

    /**
     * Wraps bindec (throws on invalid digits).
     */
    public static function binToDec(string $binary): int|float
    {
        self::assertDigits($binary, 2);

        return bindec($binary);
    }

    /**
     * Wraps ceil.
     */
    public static function ceil(int|float $number): float
    {
        return ceil($number);
    }

    /**
     * Wraps cos.
     */
    public static function cos(int|float $angle): float
    {
        return cos($angle);
    }

    /**
     * Wraps cosh.
     */
    public static function cosh(int|float $angle): float
    {
        return cosh($angle);
    }

    /**
     * Wraps decbin.
     */
    public static function decToBin(int $number): string
    {
        return decbin($number);
    }

    /**
     * Wraps dechex.
     */
    public static function decToHex(int $number): string
    {
        return dechex($number);
    }

    /**
     * Wraps decoct.
     */
    public static function decToOct(int $number): string
    {
        return decoct($number);
    }

    /**
     * Wraps deg2rad.
     */
    public static function degToRad(int|float $degrees): float
    {
        return deg2rad($degrees);
    }

    /**
     * Wraps exp.
     */
    public static function exp(int|float $number): float
    {
        return exp($number);
    }

    /**
     * Wraps expm1.
     */
    public static function expm1(int|float $number): float
    {
        return expm1($number);
    }

    /**
     * Wraps fdiv (IEEE division, never throws).
     */
    public static function fdiv(int|float $dividend, int|float $divisor): float
    {
        return fdiv($dividend, $divisor);
    }

    /**
     * Wraps floor.
     */
    public static function floor(int|float $number): float
    {
        return floor($number);
    }

    /**
     * Wraps fmod (throws instead of NAN).
     */
    public static function fmod(int|float $dividend, int|float $divisor): float
    {
        self::assertThat($divisor != 0, 'The divisor cannot be zero.');

        return fmod($dividend, $divisor);
    }

    /**
     * Wraps number_format.
     */
    public static function format(int|float $number, int $decimals = 0, ?string $decimalSeparator = '.', ?string $thousandsSeparator = ','): string
    {
        return number_format($number, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    /**
     * Wraps hexdec (throws on invalid digits).
     */
    public static function hexToDec(string $hex): int|float
    {
        self::assertDigits($hex, 16);

        return hexdec($hex);
    }

    /**
     * Wraps hypot.
     */
    public static function hypot(int|float $x, int|float $y): float
    {
        return hypot($x, $y);
    }

    /**
     * Wraps intdiv.
     */
    public static function intdiv(int $dividend, int $divisor): int
    {
        return intdiv($dividend, $divisor);
    }

    /**
     * Wraps is_finite.
     */
    public static function isFinite(float $number): bool
    {
        return is_finite($number);
    }

    /**
     * Wraps is_infinite.
     */
    public static function isInfinite(float $number): bool
    {
        return is_infinite($number);
    }

    /**
     * Wraps is_nan.
     */
    public static function isNan(float $number): bool
    {
        return is_nan($number);
    }

    /**
     * Wraps log (throws instead of NAN/-INF).
     */
    public static function log(int|float $number, int|float $base = M_E): float
    {
        self::assertThat($number > 0, 'The logarithm is only defined for numbers greater than zero.');
        self::assertThat($base > 0 && $base != 1, 'The logarithm base must be greater than zero and different from one.');

        return log($number, $base);
    }

    /**
     * Wraps log10 (throws instead of NAN/-INF).
     */
    public static function log10(int|float $number): float
    {
        self::assertThat($number > 0, 'The logarithm is only defined for numbers greater than zero.');

        return log10($number);
    }

    /**
     * Wraps log1p (throws instead of NAN/-INF).
     */
    public static function log1p(int|float $number): float
    {
        self::assertThat($number > -1, 'log1p() is only defined for numbers greater than -1.');

        return log1p($number);
    }

    /**
     * Wraps max.
     */
    public static function max(int|float ...$values): int|float
    {
        return max($values);
    }

    /**
     * Wraps min.
     */
    public static function min(int|float ...$values): int|float
    {
        return min($values);
    }

    /**
     * Wraps mt_getrandmax.
     */
    public static function mtMax(): int
    {
        return mt_getrandmax();
    }

    /**
     * Wraps mt_rand.
     */
    public static function mtRand(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }

    /**
     * Wraps mt_srand.
     */
    public static function mtSeed(int $seed): void
    {
        mt_srand($seed);
    }

    /**
     * Wraps octdec (throws on invalid digits).
     */
    public static function octToDec(string $octal): int|float
    {
        self::assertDigits($octal, 8);

        return octdec($octal);
    }

    /**
     * Wraps pi.
     */
    public static function pi(): float
    {
        return M_PI;
    }

    /**
     * Wraps pow (throws instead of INF/NAN for undefined results).
     */
    public static function pow(int|float $base, int|float $exponent): int|float
    {
        self::assertThat($base != 0 || $exponent >= 0, 'Zero cannot be raised to a negative power.');

        $result = $base ** $exponent;
        self::assertThat(!\is_float($result) || !is_nan($result), 'The result is not a real number.');

        return $result;
    }

    /**
     * Wraps rad2deg.
     */
    public static function radToDeg(int|float $radians): float
    {
        return rad2deg($radians);
    }

    /**
     * Wraps random_int (cryptographically secure).
     */
    public static function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    /**
     * Wraps round.
     */
    public static function round(int|float $number, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): float
    {
        return round($number, $precision, $mode);
    }

    /**
     * Wraps sin.
     */
    public static function sin(int|float $angle): float
    {
        return sin($angle);
    }

    /**
     * Wraps sinh.
     */
    public static function sinh(int|float $angle): float
    {
        return sinh($angle);
    }

    /**
     * Wraps sqrt (throws instead of NAN).
     */
    public static function sqrt(int|float $number): float
    {
        self::assertThat($number >= 0, 'Cannot take the square root of a negative number.');

        return sqrt($number);
    }

    /**
     * Wraps tan.
     */
    public static function tan(int|float $angle): float
    {
        return tan($angle);
    }

    /**
     * Wraps tanh.
     */
    public static function tanh(int|float $angle): float
    {
        return tanh($angle);
    }

    private static function assertThat(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new InvalidArgumentException($message);
        }
    }

    private static function assertDigits(string $number, int $base): void
    {
        $digits = substr('0123456789abcdefghijklmnopqrstuvwxyz', 0, $base);

        if (preg_match('/^[' . $digits . ']*$/i', $number) !== 1) {
            throw new InvalidArgumentException(\sprintf('"%s" is not a valid base %d number.', $number, $base));
        }
    }
}
