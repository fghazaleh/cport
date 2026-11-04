<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LazyArrTest extends TestCase
{
    public function testingNothingRunsUntilConsumed(): void
    {
        $calls = 0;
        $lazy = LazyArr::from([1, 2, 3])->map(function ($i) use (&$calls) {
            $calls++;

            return $i * 2;
        });

        $this->assertSame(0, $calls);
        $this->assertSame([2, 4, 6], $lazy->all());
        $this->assertSame(3, $calls);
    }

    public function testingTakeStopsEarlyOnInfiniteSource(): void
    {
        $naturals = LazyArr::from(static function (): \Generator {
            $i = 1;
            while (true) {
                yield $i++;
            }
        });

        $this->assertSame([2, 4, 6], $naturals->filter(fn($i) => $i % 2 === 0)->take(3)->values()->all());
    }

    public function testingSkipAndChunk(): void
    {
        $lazy = LazyArr::range(1, 7);

        $this->assertSame([3, 4, 5], $lazy->skip(2)->take(3)->values()->all());
        $this->assertSame([[1, 2, 3], [4, 5, 6], [7]], $lazy->chunk(3)->values()->all());
    }

    public function testingReduceFirstAndEach(): void
    {
        $lazy = LazyArr::range(1, 5);

        $this->assertSame(15, $lazy->reduce(fn($carry, $i) => $carry + $i, 0));
        $this->assertSame(4, $lazy->first(fn($i) => $i > 3));
        $this->assertSame('none', $lazy->first(fn($i) => $i > 9, 'none'));

        $seen = [];
        $lazy->each(function ($i) use (&$seen) {
            $seen[] = $i;

            return $i < 3;
        });
        $this->assertSame([1, 2, 3], $seen);
    }

    public function testingClosureSourceCanBeIteratedTwice(): void
    {
        $lazy = LazyArr::from(static fn() => yield from [1, 2]);

        $this->assertSame([1, 2], $lazy->all());
        $this->assertSame([1, 2], $lazy->all());
    }

    public function testingConversionRoundTrip(): void
    {
        $arr = (new Arr([1, 2, 3, 4]))->lazy()->filter(fn($i) => $i > 2)->toArr();

        $this->assertSame([2 => 3, 3 => 4], $arr->all());
    }

    public function testingIterable(): void
    {
        $result = [];
        foreach (LazyArr::range(1, 3) as $value) {
            $result[] = $value;
        }

        $this->assertSame([1, 2, 3], $result);
    }

    public function testingInvalidArgumentsThrow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        LazyArr::range(1, 3)->chunk(0);
    }
}
