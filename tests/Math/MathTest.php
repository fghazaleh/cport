<?php

declare(strict_types=1);

namespace FG\Support\Math;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testingPi(): void
    {
        $this->assertSame(M_PI, Math::pi());
    }

    public function testingAbs(): void
    {
        $this->assertSame(5, Math::abs(-5));
        $this->assertSame(5.5, Math::abs(-5.5));
    }

    public function testingCeilFloorAndAliases(): void
    {
        $this->assertSame(4.0, Math::ceil(3.2));
        $this->assertSame(4.0, Math::roundUp(3.2));
        $this->assertSame(3.0, Math::floor(3.8));
        $this->assertSame(3.0, Math::roundDown(3.8));
    }

    public function testingRound(): void
    {
        $this->assertSame(3.0, Math::round(2.5));
        $this->assertSame(3.14, Math::round(3.14159, 2));
        $this->assertSame(2.0, Math::round(2.5, 0, PHP_ROUND_HALF_EVEN));
    }

    public function testingLog(): void
    {
        $this->assertSame(3.0, Math::log(8, 2));
        $this->assertEqualsWithDelta(1.0, Math::log(M_E), 1e-12);
        $this->assertEqualsWithDelta(2.0, Math::log10(100), 1e-12);
        $this->assertEqualsWithDelta(10.0, Math::log2(1024), 1e-12);
    }

    #[DataProvider('invalidLogArguments')]
    public function testingLogWithInvalidArgumentsThrows(int|float $value, int|float $base): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log($value, $base);
    }

    public static function invalidLogArguments(): array
    {
        return [
            'zero value' => [0, 10],
            'negative value' => [-1, 10],
            'base one' => [10, 1],
            'zero base' => [10, 0],
            'negative base' => [10, -2],
        ];
    }

    public function testingPowAndSqrt(): void
    {
        $this->assertSame(8, Math::pow(2, 3));
        $this->assertSame(0.25, Math::pow(2, -2));
        $this->assertSame(4.0, Math::sqrt(16));
    }

    public function testingSqrtOfNegativeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::sqrt(-1);
    }

    public function testingPowDomainErrorsThrow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::pow(0, -1);
    }

    public function testingPowNotARealNumberThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::pow(-8, 0.5);
    }

    public function testingMinMaxSum(): void
    {
        $this->assertSame(9, Math::max(1, 9, 4));
        $this->assertSame(1.5, Math::min(2, 1.5, 3));
        $this->assertSame(10, Math::sum(1, 2, 3, 4));
        $this->assertSame(0, Math::sum());
    }

    public function testingMaxWithoutValuesThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::max();
    }

    public function testingMinWithoutValuesThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::min();
    }

    public function testingAvg(): void
    {
        $this->assertSame(2.5, Math::avg(1, 2, 3, 4));
    }

    public function testingAvgWithoutValuesThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::avg();
    }

    public function testingMedian(): void
    {
        $this->assertSame(3, Math::median(5, 1, 3));
        $this->assertSame(2.5, Math::median(4, 1, 2, 3));
        $this->assertSame(7, Math::median(7));
    }

    public function testingMode(): void
    {
        $this->assertSame([2], Math::mode(1, 2, 2, 3));
        $this->assertSame([1, 3], Math::mode(3, 1, 3, 1, 2));
        $this->assertSame([1.5], Math::mode(1.5, 2, 1.5));
    }

    public function testingPercentile(): void
    {
        $values = [15, 20, 35, 40, 50];

        $this->assertSame(15.0, Math::percentile($values, 0));
        $this->assertSame(35.0, Math::percentile($values, 50));
        $this->assertSame(50.0, Math::percentile($values, 100));
        $this->assertEqualsWithDelta(29.0, Math::percentile($values, 40), 1e-12);
        $this->assertSame(35.0, Math::percentile([50, 15, 40, 35, 20], 50), 'input order must not matter');
    }

    public function testingPercentileOutOfRangeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::percentile([1, 2], 101);
    }

    public function testingPercentileOfEmptyThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::percentile([], 50);
    }

    public function testingClampAndBetween(): void
    {
        $this->assertSame(10, Math::clamp(15, 0, 10));
        $this->assertSame(0, Math::clamp(-5, 0, 10));
        $this->assertSame(5, Math::clamp(5, 0, 10));

        $this->assertTrue(Math::between(5, 5, 10));
        $this->assertFalse(Math::between(5, 5, 10, false));
        $this->assertTrue(Math::between(7, 5, 10, false));
    }

    public function testingClampWithInvertedRangeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::clamp(1, 10, 0);
    }

    public function testingPercentage(): void
    {
        $this->assertSame(25.0, Math::percentage(1, 4));
        $this->assertSame(33.33, Math::percentage(1, 3));
        $this->assertSame(33.0, Math::percentage(1, 3, 0));
    }

    public function testingPercentageOfZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::percentage(1, 0);
    }
}
