<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NumberTest extends TestCase
{
    public function testCreation(): void
    {
        $this->assertSame(5, Number::of(5)->value());
        $this->assertSame(5.5, Number::of(5.5)->value());
        $this->assertSame(12, Number::of('12')->value());
        $this->assertSame(1.5, Number::of('1.5')->value());
        $this->assertSame(7, Number::of(Text::of('7'))->value(), 'any Stringable works');

        $number = Number::of(3);
        $this->assertSame($number, Number::of($number));
    }

    public function testNonNumericStringThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of('abc');
    }

    public function testArithmeticReturnsNewInstances(): void
    {
        $number = Number::of(10);

        $this->assertSame(13, $number->plus(3)->value());
        $this->assertSame(7, $number->minus(3)->value());
        $this->assertSame(30, $number->multiply(3)->value());
        $this->assertSame(2.5, $number->divide(4)->value());
        $this->assertSame(1, $number->modulo(3)->value());
        $this->assertSame(1.0, Number::of(5.5)->modulo(2.25)->value());
        $this->assertSame(100, $number->power(2)->value());
        $this->assertSame(10, $number->value(), 'the original must not change');
    }

    public function testOperationsAcceptNumbers(): void
    {
        $this->assertSame(15, Number::of(10)->plus(Number::of(5))->value());
    }

    public function testChaining(): void
    {
        $this->assertSame(6, Number::of(1)->plus(2)->multiply(4)->minus(6)->value());
    }

    public function testDivideByZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of(1)->divide(0);
    }

    public function testModuloByZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of(1)->modulo(0);
    }

    public function testMathOperations(): void
    {
        $this->assertSame(3.0, Number::of(9)->squareRoot()->value());
        $this->assertSame(4, Number::of(-4)->abs()->value());
        $this->assertSame(3.14, Number::of(3.14159)->round(2)->value());
        $this->assertSame(4.0, Number::of(3.2)->ceil()->value());
        $this->assertSame(3.0, Number::of(3.8)->floor()->value());
    }

    public function testSquareRootOfNegativeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of(-1)->squareRoot();
    }

    public function testClamp(): void
    {
        $this->assertSame(10, Number::of(15)->clamp(0, 10)->value());
        $this->assertSame(0, Number::of(-5)->clamp(0, 10)->value());
        $this->assertSame(5, Number::of(5)->clamp(0, 10)->value());
    }

    public function testClampWithInvertedRangeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of(1)->clamp(10, 0);
    }

    public function testComparisons(): void
    {
        $number = Number::of(5);

        $this->assertSame(0, $number->compare(5));
        $this->assertSame(1, $number->compare(4));
        $this->assertSame(-1, $number->compare(Number::of(6)));
        $this->assertTrue($number->equals(5));
        $this->assertFalse($number->equals(5.1));
        $this->assertTrue(Number::of(0.1 + 0.2)->equals(0.3, 1e-9));
        $this->assertTrue($number->isBetween(1, 5));
        $this->assertFalse($number->isBetween(1, 5, false));
        $this->assertTrue($number->isBetween(4, 6, false));
    }

    public function testPredicates(): void
    {
        $this->assertTrue(Number::of(4)->isEven());
        $this->assertTrue(Number::of(3)->isOdd());
        $this->assertTrue(Number::of(4.0)->isWhole());
        $this->assertFalse(Number::of(4.5)->isWhole());
        $this->assertFalse(Number::of(4.5)->isEven());
        $this->assertFalse(Number::of(4.5)->isOdd());
    }

    public function testPercentOf(): void
    {
        $this->assertSame(25, (int) Number::of(1)->percentOf(4)->value());
        $this->assertSame(33.33, Number::of(1)->percentOf(3)->value());
        $this->assertSame(33.0, Number::of(1)->percentOf(3, 0)->value());
    }

    public function testPercentOfZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of(1)->percentOf(0);
    }

    public function testFormat(): void
    {
        $this->assertSame('1,234.50', Number::of(1234.5)->format(2));
        $this->assertSame('1.234,5', Number::of(1234.5)->format(1, ',', '.'));
        $this->assertSame('1,235', Number::of(1234.5)->format());
    }

    #[DataProvider('ordinals')]
    public function testToOrdinal(int $number, string $expected): void
    {
        $this->assertSame($expected, Number::of($number)->toOrdinal());
    }

    /**
     * @return list<array{int, string}>
     */
    public static function ordinals(): array
    {
        return [
            [1, '1st'], [2, '2nd'], [3, '3rd'], [4, '4th'],
            [11, '11th'], [12, '12th'], [13, '13th'],
            [21, '21st'], [22, '22nd'], [23, '23rd'],
            [101, '101st'], [111, '111th'], [0, '0th'], [-2, '-2nd'],
        ];
    }

    public function testOrdinalOfAFractionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Number::of(1.5)->toOrdinal();
    }

    public function testConversions(): void
    {
        $this->assertSame(3, Number::of(3.9)->toInt());
        $this->assertSame(3.0, Number::of(3)->toFloat());
        $this->assertSame('3.5', (string) Number::of(3.5));
        $this->assertSame('[1,2.5]', json_encode([Number::of(1), Number::of(2.5)]));
    }
}
