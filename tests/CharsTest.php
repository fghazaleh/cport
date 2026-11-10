<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CharsTest extends TestCase
{
    public function testCreationSplitsIntoCharactersNotBytes(): void
    {
        $chars = Chars::of('héllo');

        $this->assertSame(['h', 'é', 'l', 'l', 'o'], $chars->toArray());
        $this->assertCount(5, $chars);
        $this->assertSame([], Chars::of()->toArray());
        $this->assertTrue(Chars::of()->isEmpty());
        $this->assertSame('ab', Chars::of(Text::of('ab'))->toString(), 'any Stringable works');
    }

    public function testFromCodes(): void
    {
        $this->assertSame('Aé', Chars::fromCodes([65, 233])->toString());
        $this->assertSame([65, 233], Chars::of('Aé')->codes());
    }

    public function testFromInvalidCodeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Chars::fromCodes([-1]);
    }

    public function testAccessors(): void
    {
        $chars = Chars::of('héllo');

        $this->assertSame('h', $chars->first());
        $this->assertSame('o', $chars->last());
        $this->assertSame('é', $chars->at(1));
        $this->assertSame('l', $chars->at(-2));
        $this->assertNull($chars->at(10));
        $this->assertNull($chars->at(-10));
        $this->assertNull(Chars::of()->first());
        $this->assertNull(Chars::of()->last());
        $this->assertTrue($chars->contains('é'));
        $this->assertFalse($chars->contains('z'));
    }

    public function testFrequency(): void
    {
        $this->assertSame(['a' => 2, 'b' => 1], Chars::of('aba')->frequency());
        $this->assertSame(['é' => 2], Chars::of('éé')->frequency());
    }

    public function testFilterRejectAndMap(): void
    {
        $chars = Chars::of('a1b2');

        $this->assertSame('ab', $chars->filter(fn($c) => ctype_alpha($c))->toString());
        $this->assertSame('12', $chars->reject(fn($c) => ctype_alpha($c))->toString());
        $this->assertSame('A1B2', $chars->map(strtoupper(...))->toString());
        $this->assertSame('aa11bb22', $chars->map(fn($c) => $c . $c)->toString());
        $this->assertSame(['a', 'a', '1', 'a'], Chars::of('a1')->map(fn($c) => $c . 'a')->toArray());
        $this->assertSame(['a', '1'], Chars::of('a01')->filter()->toArray(), 'the falsy character "0" is removed');
    }

    public function testReverseUniqueSort(): void
    {
        $this->assertSame('olléh', Chars::of('héllo')->reverse()->toString());
        $this->assertSame('hélo', Chars::of('héllo')->unique()->toString());
        $this->assertSame('abc', Chars::of('cab')->sort()->toString());
        $this->assertSame('cba', Chars::of('cab')->sort(true)->toString());
    }

    public function testTakeAndSkip(): void
    {
        $chars = Chars::of('abcdef');

        $this->assertSame('abc', $chars->take(3)->toString());
        $this->assertSame('def', $chars->take(-3)->toString());
        $this->assertSame('def', $chars->skip(3)->toString());
        $this->assertSame('', $chars->skip(10)->toString());
    }

    public function testCharacterClasses(): void
    {
        $chars = Chars::of("Ab1 é,Z9\t");

        $this->assertSame('AbéZ', $chars->letters()->toString());
        $this->assertSame('19', $chars->digits()->toString());
        $this->assertSame(" \t", $chars->spaces()->toString());
        $this->assertSame('AZ', $chars->uppercase()->toString());
        $this->assertSame('bé', $chars->lowercase()->toString());
        $this->assertSame(',', $chars->punctuation()->toString());
    }

    public function testIterationConversionAndImmutability(): void
    {
        $chars = Chars::of('ab');
        $chars->reverse()->map(strtoupper(...));

        $seen = [];
        foreach ($chars as $index => $char) {
            $seen[$index] = $char;
        }

        $this->assertSame(['a', 'b'], $seen);
        $this->assertSame('ab', (string) $chars);
        $this->assertSame('["a","b"]', json_encode($chars));
    }
}
