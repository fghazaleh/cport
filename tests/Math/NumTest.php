<?php

declare(strict_types=1);

namespace FG\Support\Math;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NumTest extends TestCase
{
    public function testingCreation(): void
    {
        $this->assertSame(5, Num::of(5)->value());
        $this->assertSame(5.5, Num::of(5.5)->value());
        $this->assertSame(12, Num::of('12')->value());
        $this->assertSame(1.5, Num::of('1.5')->value());

        $num = Num::of(3);
        $this->assertSame($num, Num::of($num));
    }

    public function testingNonNumericStringThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Num::of('abc');
    }

    public function testingArithmeticIsImmutable(): void
    {
        $num = Num::of(10);

        $this->assertSame(13, $num->add(3)->value());
        $this->assertSame(7, $num->sub(3)->value());
        $this->assertSame(30, $num->mul(3)->value());
        $this->assertSame(2.5, $num->div(4)->value());
        $this->assertSame(1, $num->mod(3)->value());
        $this->assertSame(1.0, Num::of(5.5)->mod(2.25)->value());
        $this->assertSame(100, $num->pow(2)->value());
        $this->assertSame(10, $num->value(), 'the original must not change');
    }

    public function testingOperationsAcceptNumInstances(): void
    {
        $this->assertSame(15, Num::of(10)->add(Num::of(5))->value());
    }

    public function testingChaining(): void
    {
        $this->assertSame(6, Num::of(1)->add(2)->mul(4)->sub(6)->value());
    }

    public function testingDivisionByZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Num::of(1)->div(0);
    }

    public function testingModuloByZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Num::of(1)->mod(0);
    }

    public function testingMathOperations(): void
    {
        $this->assertSame(3.0, Num::of(9)->sqrt()->value());
        $this->assertSame(4, Num::of(-4)->abs()->value());
        $this->assertSame(3.14, Num::of(3.14159)->round(2)->value());
        $this->assertSame(4.0, Num::of(3.2)->ceil()->value());
        $this->assertSame(3.0, Num::of(3.8)->floor()->value());
        $this->assertSame(10, Num::of(15)->clamp(0, 10)->value());
    }

    public function testingComparisons(): void
    {
        $num = Num::of(5);

        $this->assertTrue($num->isBetween(1, 5));
        $this->assertFalse($num->isBetween(1, 5, false));
        $this->assertSame(0, $num->compare(5));
        $this->assertSame(1, $num->compare(4));
        $this->assertSame(-1, $num->compare(Num::of(6)));
        $this->assertTrue($num->equals(5));
        $this->assertFalse($num->equals(5.1));
        $this->assertTrue(Num::of(0.1 + 0.2)->equals(0.3, 1e-9));
    }

    public function testingPredicates(): void
    {
        $this->assertTrue(Num::of(0)->isZero());
        $this->assertTrue(Num::of(2)->isPositive());
        $this->assertTrue(Num::of(-2)->isNegative());
        $this->assertTrue(Num::of(4)->isEven());
        $this->assertTrue(Num::of(3)->isOdd());
        $this->assertTrue(Num::of(4.0)->isWhole());
        $this->assertFalse(Num::of(4.5)->isWhole());
        $this->assertFalse(Num::of(4.5)->isEven());
        $this->assertFalse(Num::of(4.5)->isOdd());
    }

    public function testingPercentOf(): void
    {
        $this->assertSame(25.0, Num::of(1)->percentOf(4));
    }

    public function testingFormat(): void
    {
        $this->assertSame('1,234.50', Num::of(1234.5)->format(2));
        $this->assertSame('1.234,5', Num::of(1234.5)->format(1, ',', '.'));
        $this->assertSame('1,235', Num::of(1234.5)->format());
    }

    #[DataProvider('ordinals')]
    public function testingToOrdinal(int $number, string $expected): void
    {
        $this->assertSame($expected, Num::of($number)->toOrdinal());
    }

    public static function ordinals(): array
    {
        return [
            [1, '1st'], [2, '2nd'], [3, '3rd'], [4, '4th'],
            [11, '11th'], [12, '12th'], [13, '13th'],
            [21, '21st'], [22, '22nd'], [23, '23rd'],
            [101, '101st'], [111, '111th'], [0, '0th'], [-2, '-2nd'],
        ];
    }

    public function testingOrdinalOfFractionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Num::of(1.5)->toOrdinal();
    }

    public function testingConversions(): void
    {
        $this->assertSame(3, Num::of(3.9)->toInt());
        $this->assertSame(3.0, Num::of(3)->toFloat());
        $this->assertSame('3.5', (string) Num::of(3.5));
        $this->assertSame('[1,2.5]', json_encode([Num::of(1), Num::of(2.5)]));
    }

    public function testingReadableAliases(): void
    {
        $num = Num::of(10);

        $this->assertSame(13, $num->plus(3)->value());
        $this->assertSame(7, $num->minus(3)->value());
        $this->assertSame(30, $num->multiply(3)->value());
        $this->assertSame(2.5, $num->divide(4)->value());
        $this->assertSame(1, $num->modulo(3)->value());
        $this->assertSame(6, Num::of(1)->plus(2)->multiply(4)->minus(6)->value());
    }

    public function testingAliasesShareTheSameErrors(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Num::of(1)->divide(0);
    }

    public function testingModuloAliasByZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Num::of(1)->modulo(0);
    }
}
