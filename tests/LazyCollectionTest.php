<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LazyCollectionTest extends TestCase
{
    public function testNothingRunsUntilTheResultIsConsumed(): void
    {
        $calls = 0;
        $lazy = LazyCollection::of([1, 2, 3])->map(function ($item) use (&$calls) {
            $calls++;

            return $item * 2;
        });

        $this->assertSame(0, $calls);
        $this->assertSame([2, 4, 6], $lazy->all());
        $this->assertSame(3, $calls);
    }

    public function testTakeStopsEarlyOnAnInfiniteSource(): void
    {
        $naturals = LazyCollection::of(static function (): \Generator {
            $i = 1;
            while (true) {
                yield $i++;
            }
        });

        $this->assertSame([2, 4, 6], $naturals->filter(fn($i) => $i % 2 === 0)->take(3)->values()->all());
        $this->assertSame([], $naturals->take(0)->all());
    }

    public function testFilterRejectAndMapKeepKeys(): void
    {
        $lazy = LazyCollection::of(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertSame(['b' => 2], $lazy->filter(fn($i) => $i === 2)->all());
        $this->assertSame(['a' => 1, 'c' => 3], $lazy->reject(fn($i) => $i === 2)->all());
        $this->assertSame(['a' => 2, 'b' => 4, 'c' => 6], $lazy->map(fn($i) => $i * 2)->all());
        $this->assertSame(['x' => 1], LazyCollection::of(['x' => 1, 'y' => 0, 'z' => null])->filter()->all());
    }

    public function testSkipAndChunk(): void
    {
        $lazy = LazyCollection::range(1, 7);

        $this->assertSame([3, 4, 5], $lazy->skip(2)->take(3)->values()->all());
        $this->assertSame([[1, 2, 3], [4, 5, 6], [7]], $lazy->chunk(3)->values()->all());
    }

    public function testRange(): void
    {
        $this->assertSame([1, 2, 3], LazyCollection::range(1, 3)->all());
        $this->assertSame([0, 5, 10], LazyCollection::range(0, 10, 5)->all());
        $this->assertSame([], LazyCollection::range(5, 1)->all());
    }

    public function testRangeWithInvalidStepThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        LazyCollection::range(1, 3, 0);
    }

    public function testChunkWithInvalidSizeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        LazyCollection::range(1, 3)->chunk(0);
    }

    public function testConsumingMethods(): void
    {
        $lazy = LazyCollection::range(1, 5);

        $this->assertSame(15, $lazy->reduce(fn($carry, $i) => $carry + $i, 0));
        $this->assertSame(1, $lazy->first());
        $this->assertSame(4, $lazy->first(fn($i) => $i > 3));
        $this->assertSame('none', $lazy->first(fn($i) => $i > 9, 'none'));
        $this->assertSame(5, $lazy->count());
        $this->assertFalse($lazy->isEmpty());
        $this->assertTrue(LazyCollection::of([])->isEmpty());
    }

    public function testFirstStopsAtTheFirstMatch(): void
    {
        $seen = 0;
        $lazy = LazyCollection::range(1, 1000)->map(function ($i) use (&$seen) {
            $seen++;

            return $i;
        });

        $this->assertSame(3, $lazy->first(fn($i) => $i === 3));
        $this->assertSame(3, $seen);
    }

    public function testAClosureSourceCanBeIteratedTwice(): void
    {
        $lazy = LazyCollection::of(static fn() => yield from [1, 2]);

        $this->assertSame([1, 2], $lazy->all());
        $this->assertSame([1, 2], $lazy->all());
    }

    public function testAcceptsAnyIterable(): void
    {
        $this->assertSame([1, 2], LazyCollection::of(new \ArrayIterator([1, 2]))->all());
        $this->assertSame([1, 2], LazyCollection::of(Collection::of([1, 2]))->all(), 'a Collection is iterable');
    }

    public function testCanBeIteratedWithForeach(): void
    {
        $result = [];
        foreach (LazyCollection::range(1, 3) as $value) {
            $result[] = $value;
        }

        $this->assertSame([1, 2, 3], $result);
    }

    public function testCollectionAcceptsALazyCollection(): void
    {
        $this->assertSame([2, 4], Collection::of(LazyCollection::range(1, 4)->filter(fn($i) => $i % 2 === 0))->values()->all());
    }
}
