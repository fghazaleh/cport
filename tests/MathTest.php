<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

final class MathTest extends WrapperTestCase
{
    public function testAbs(): void
    {
        $a = [-5];
        self::assertSameResult(5, Math::abs(...$a));
        $a = [-5.5];
        self::assertSameResult(5.5, Math::abs(...$a));
        $a = [3];
        self::assertSameResult(3, Math::abs(...$a));
    }

    public function testAcos(): void
    {
        $a = [1];
        self::assertSameResult(0.0, Math::acos(...$a));
        $a = [0];
        self::assertSameResult(M_PI_2, Math::acos(...$a));
    }

    public function testAcosThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::acos(...[-2]);
    }

    public function testAcosh(): void
    {
        $a = [1];
        self::assertSameResult(0.0, Math::acosh(...$a));
        $a = [2];
        self::assertSameResult(acosh(2), Math::acosh(...$a));
    }

    public function testAcoshThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::acosh(...[0.5]);
    }

    public function testAsin(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::asin(...$a));
        $a = [1];
        self::assertSameResult(M_PI_2, Math::asin(...$a));
    }

    public function testAsinThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::asin(...[2]);
    }

    public function testAsinh(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::asinh(...$a));
        $a = [1];
        self::assertSameResult(asinh(1), Math::asinh(...$a));
    }

    public function testAtan(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::atan(...$a));
        $a = [1];
        self::assertSameResult(M_PI_4, Math::atan(...$a));
    }

    public function testAtan2(): void
    {
        $a = [1, 1];
        self::assertSameResult(M_PI_4, Math::atan2(...$a));
        $a = [0, -1];
        self::assertSameResult(M_PI, Math::atan2(...$a));
    }

    public function testAtanh(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::atanh(...$a));
        $a = [0.5];
        self::assertSameResult(atanh(0.5), Math::atanh(...$a));
    }

    public function testAtanhThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::atanh(...[1]);
    }

    public function testBaseConvert(): void
    {
        $a = ['ff', 16, 2];
        self::assertSameResult('11111111', Math::baseConvert(...$a));
        $a = ['255', 10, 16];
        self::assertSameResult('ff', Math::baseConvert(...$a));
        $a = ['Z', 36, 10];
        self::assertSameResult('35', Math::baseConvert(...$a));
    }

    public function testBaseConvertThrows1(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::baseConvert(...['2', 2, 10]);
    }

    public function testBaseConvertThrows2(): void
    {
        $this->expectException(\ValueError::class);
        Math::baseConvert(...['1', 1, 10]);
    }

    public function testBinToDec(): void
    {
        $a = ['1010'];
        self::assertSameResult(10, Math::binToDec(...$a));
        $a = [''];
        self::assertSameResult(0, Math::binToDec(...$a));
    }

    public function testBinToDecThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::binToDec(...['102']);
    }

    public function testCeil(): void
    {
        $a = [3.2];
        self::assertSameResult(4.0, Math::ceil(...$a));
        $a = [-3.2];
        self::assertSameResult(-3.0, Math::ceil(...$a));
        $a = [5];
        self::assertSameResult(5.0, Math::ceil(...$a));
    }

    public function testCos(): void
    {
        $a = [0];
        self::assertSameResult(1.0, Math::cos(...$a));
        $a = [M_PI];
        self::assertSameResult(-1.0, Math::cos(...$a));
    }

    public function testCosh(): void
    {
        $a = [0];
        self::assertSameResult(1.0, Math::cosh(...$a));
        $a = [1];
        self::assertSameResult(cosh(1), Math::cosh(...$a));
    }

    public function testDecToBin(): void
    {
        $a = [10];
        self::assertSameResult('1010', Math::decToBin(...$a));
        $a = [0];
        self::assertSameResult('0', Math::decToBin(...$a));
    }

    public function testDecToHex(): void
    {
        $a = [255];
        self::assertSameResult('ff', Math::decToHex(...$a));
    }

    public function testDecToOct(): void
    {
        $a = [511];
        self::assertSameResult('777', Math::decToOct(...$a));
    }

    public function testDegToRad(): void
    {
        $a = [180];
        self::assertSameResult(M_PI, Math::degToRad(...$a));
        $a = [90];
        self::assertSameResult(M_PI_2, Math::degToRad(...$a));
    }

    public function testExp(): void
    {
        $a = [0];
        self::assertSameResult(1.0, Math::exp(...$a));
        $a = [1];
        self::assertSameResult(M_E, Math::exp(...$a));
    }

    public function testExpm1(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::expm1(...$a));
        $a = [1e-10];
        self::assertSameResult(expm1(1e-10), Math::expm1(...$a));
    }

    public function testFdiv(): void
    {
        $a = [1, 4];
        self::assertSameResult(0.25, Math::fdiv(...$a));
        $a = [1, 0];
        self::assertSameResult(INF, Math::fdiv(...$a));
        $a = [-1, 0];
        self::assertSameResult(-INF, Math::fdiv(...$a));
    }

    public function testFloor(): void
    {
        $a = [3.8];
        self::assertSameResult(3.0, Math::floor(...$a));
        $a = [-3.2];
        self::assertSameResult(-4.0, Math::floor(...$a));
        $a = [5];
        self::assertSameResult(5.0, Math::floor(...$a));
    }

    public function testFmod(): void
    {
        $a = [5.5, 2.25];
        self::assertSameResult(fmod(5.5, 2.25), Math::fmod(...$a));
        $a = [10, 3];
        self::assertSameResult(1.0, Math::fmod(...$a));
    }

    public function testFmodThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::fmod(...[1, 0]);
    }

    public function testFormat(): void
    {
        $a = [1234.5678, 2];
        self::assertSameResult('1,234.57', Math::format(...$a));
        $a = [1234.5, 1, ',', '.'];
        self::assertSameResult('1.234,5', Math::format(...$a));
        $a = [1234.5];
        self::assertSameResult(number_format(1234.5), Math::format(...$a));
    }

    public function testHexToDec(): void
    {
        $a = ['ff'];
        self::assertSameResult(255, Math::hexToDec(...$a));
        $a = ['FF'];
        self::assertSameResult(255, Math::hexToDec(...$a));
    }

    public function testHexToDecThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::hexToDec(...['xyz']);
    }

    public function testHypot(): void
    {
        $a = [3, 4];
        self::assertSameResult(5.0, Math::hypot(...$a));
    }

    public function testIntdiv(): void
    {
        $a = [7, 2];
        self::assertSameResult(3, Math::intdiv(...$a));
        $a = [-7, 2];
        self::assertSameResult(-3, Math::intdiv(...$a));
    }

    public function testIntdivThrows(): void
    {
        $this->expectException(\DivisionByZeroError::class);
        Math::intdiv(...[1, 0]);
    }

    public function testIsFinite(): void
    {
        $a = [1.5];
        self::assertSameResult(true, Math::isFinite(...$a));
        $a = [INF];
        self::assertSameResult(false, Math::isFinite(...$a));
        $a = [NAN];
        self::assertSameResult(false, Math::isFinite(...$a));
    }

    public function testIsInfinite(): void
    {
        $a = [INF];
        self::assertSameResult(true, Math::isInfinite(...$a));
        $a = [-INF];
        self::assertSameResult(true, Math::isInfinite(...$a));
        $a = [1.5];
        self::assertSameResult(false, Math::isInfinite(...$a));
    }

    public function testIsNan(): void
    {
        $a = [NAN];
        self::assertSameResult(true, Math::isNan(...$a));
        $a = [1.5];
        self::assertSameResult(false, Math::isNan(...$a));
    }

    public function testLog(): void
    {
        $a = [8, 2];
        self::assertSameResult(3.0, Math::log(...$a));
        $a = [M_E];
        self::assertSameResult(1.0, Math::log(...$a));
        $a = [100, 10];
        self::assertSameResult(2.0, Math::log(...$a));
    }

    public function testLogThrows1(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log(...[0]);
    }

    public function testLogThrows2(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log(...[-1]);
    }

    public function testLogThrows3(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log(...[10, 1]);
    }

    public function testLogThrows4(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log(...[10, 0]);
    }

    public function testLog10(): void
    {
        $a = [1000];
        self::assertSameResult(3.0, Math::log10(...$a));
    }

    public function testLog10Throws(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log10(...[0]);
    }

    public function testLog1p(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::log1p(...$a));
        $a = [1e-10];
        self::assertSameResult(log1p(1e-10), Math::log1p(...$a));
    }

    public function testLog1pThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::log1p(...[-1]);
    }

    public function testMax(): void
    {
        $a = [1, 9, 4];
        self::assertSameResult(9, Math::max(...$a));
        $a = [2.5, 1];
        self::assertSameResult(2.5, Math::max(...$a));
    }

    public function testMaxThrows(): void
    {
        $this->expectException(\ValueError::class);
        Math::max(...[]);
    }

    public function testMin(): void
    {
        $a = [3, 1.5, 2];
        self::assertSameResult(1.5, Math::min(...$a));
    }

    public function testMinThrows(): void
    {
        $this->expectException(\ValueError::class);
        Math::min(...[]);
    }

    public function testMtMax(): void
    {
        $a = [];
        self::assertSameResult(mt_getrandmax(), Math::mtMax(...$a));
    }

    public function testMtRand(): void
    {
        $a = [5, 5];
        self::assertSameResult(5, Math::mtRand(...$a));
        $a = [1, 10];
        $result = Math::mtRand(...$a);
        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertLessThanOrEqual(10, $result);
    }

    public function testMtSeed(): void
    {
        $a = [42];
        Math::mtSeed(...$a);
        $first = mt_rand(1, 1000);
        Math::mtSeed(...$a);
        $this->assertSame($first, mt_rand(1, 1000));
    }

    public function testOctToDec(): void
    {
        $a = ['777'];
        self::assertSameResult(511, Math::octToDec(...$a));
    }

    public function testOctToDecThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::octToDec(...['9']);
    }

    public function testPi(): void
    {
        $a = [];
        self::assertSameResult(pi(), Math::pi(...$a));
    }

    public function testPow(): void
    {
        $a = [2, 3];
        self::assertSameResult(8, Math::pow(...$a));
        $a = [2, -2];
        self::assertSameResult(0.25, Math::pow(...$a));
        $a = [2.5, 2];
        self::assertSameResult(6.25, Math::pow(...$a));
    }

    public function testPowThrows1(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::pow(...[0, -1]);
    }

    public function testPowThrows2(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::pow(...[-8, 0.5]);
    }

    public function testRadToDeg(): void
    {
        $a = [M_PI];
        self::assertSameResult(180.0, Math::radToDeg(...$a));
        $a = [M_PI_2];
        self::assertSameResult(90.0, Math::radToDeg(...$a));
    }

    public function testRandomInt(): void
    {
        $a = [7, 7];
        self::assertSameResult(7, Math::randomInt(...$a));
        $a = [1, 10];
        $result = Math::randomInt(...$a);
        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertLessThanOrEqual(10, $result);
    }

    public function testRandomIntThrows(): void
    {
        $this->expectException(\ValueError::class);
        Math::randomInt(...[10, 1]);
    }

    public function testRound(): void
    {
        $a = [2.5];
        self::assertSameResult(3.0, Math::round(...$a));
        $a = [3.14159, 2];
        self::assertSameResult(3.14, Math::round(...$a));
        $a = [1234, -2];
        self::assertSameResult(1200.0, Math::round(...$a));
        $a = [2.5, 0, PHP_ROUND_HALF_EVEN];
        self::assertSameResult(2.0, Math::round(...$a));
        $a = [-2.5];
        self::assertSameResult(-3.0, Math::round(...$a));
    }

    public function testSin(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::sin(...$a));
        $a = [M_PI_2];
        self::assertSameResult(1.0, Math::sin(...$a));
    }

    public function testSinh(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::sinh(...$a));
        $a = [1];
        self::assertSameResult(sinh(1), Math::sinh(...$a));
    }

    public function testSqrt(): void
    {
        $a = [16];
        self::assertSameResult(4.0, Math::sqrt(...$a));
        $a = [2];
        self::assertSameResult(sqrt(2), Math::sqrt(...$a));
    }

    public function testSqrtThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Math::sqrt(...[-1]);
    }

    public function testTan(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::tan(...$a));
        $a = [M_PI_4];
        self::assertSameResult(tan(M_PI_4), Math::tan(...$a));
    }

    public function testTanh(): void
    {
        $a = [0];
        self::assertSameResult(0.0, Math::tanh(...$a));
        $a = [1];
        self::assertSameResult(tanh(1), Math::tanh(...$a));
    }
}
