<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    public function testCreation(): void
    {
        $this->assertSame([1, 2], Collection::of([1, 2])->all());
        $this->assertSame([], Collection::of()->all());
        $this->assertSame(['a' => 1], Collection::of((static fn() => yield 'a' => 1)())->all());
        $this->assertSame([1, 2], Collection::of(new \ArrayIterator([1, 2]))->all());
    }

    public function testJsonRoundTrip(): void
    {
        $collection = Collection::of(['a' => 1, 'b' => [2, 3]]);

        $this->assertSame('{"a":1,"b":[2,3]}', $collection->toJson());
        $this->assertSame('{"a":1,"b":[2,3]}', json_encode($collection));
        $this->assertSame($collection->all(), Collection::fromJson($collection->toJson())->all());
    }

    public function testInvalidJsonThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collection::fromJson('{not json');
    }

    public function testScalarJsonThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collection::fromJson('42');
    }

    public function testCountingAndIterating(): void
    {
        $collection = Collection::of(['a' => 1, 'b' => 2]);

        $this->assertCount(2, $collection);
        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());
        $this->assertTrue(Collection::of()->isEmpty());

        $seen = [];
        foreach ($collection as $key => $value) {
            $seen[$key] = $value;
        }
        $this->assertSame(['a' => 1, 'b' => 2], $seen);
    }

    public function testFirstAndLast(): void
    {
        $collection = Collection::of([1, 2, 3, 4]);

        $this->assertSame(1, $collection->first());
        $this->assertSame(4, $collection->last());
        $this->assertSame(3, $collection->first(fn($i) => $i > 2));
        $this->assertSame(3, $collection->last(fn($i) => $i < 4));
        $this->assertSame('none', $collection->first(fn($i) => $i > 9, 'none'));
        $this->assertNull(Collection::of()->last());
    }

    public function testDotNotation(): void
    {
        $collection = Collection::of(['user' => ['address' => ['city' => 'Rome']], 'a.b' => 'literal']);

        $this->assertSame('Rome', $collection->get('user.address.city'));
        $this->assertSame('literal', $collection->get('a.b'));
        $this->assertSame('x', $collection->get('user.address.zip', 'x'));
        $this->assertTrue($collection->has('user.address'));
        $this->assertFalse($collection->has('user.phone'));
    }

    public function testContains(): void
    {
        $collection = Collection::of([1, 2, '3']);

        $this->assertTrue($collection->contains(2));
        $this->assertTrue($collection->contains(3));
        $this->assertFalse($collection->contains(3, true));
        $this->assertTrue($collection->contains(fn($i) => $i > 1));
        $this->assertFalse($collection->contains(fn($i) => $i > 5));
    }

    public function testKeysValuesAndImplode(): void
    {
        $collection = Collection::of(['a' => 'x', 'b' => 'y']);

        $this->assertSame(['a', 'b'], $collection->keys()->all());
        $this->assertSame(['x', 'y'], $collection->values()->all());
        $this->assertSame('x,y', $collection->implode(','));
    }

    public function testMapFilterRejectReduce(): void
    {
        $collection = Collection::of([1, 2, 3, 4]);

        $this->assertSame([2, 4, 6, 8], $collection->map(fn($i) => $i * 2)->all());
        $this->assertSame(['A', 'B'], Collection::of(['a', 'b'])->map(strtoupper(...))->all());
        $this->assertSame([1 => 2, 3 => 4], $collection->filter(fn($i) => $i % 2 === 0)->all());
        $this->assertSame([1, 3], Collection::of([1, 0, 3, null])->filter()->values()->all());
        $this->assertSame([0 => 1, 2 => 3], $collection->reject(fn($i) => $i % 2 === 0)->all());
        $this->assertSame(10, $collection->reduce(fn($carry, $i) => $carry + $i, 0));
        $this->assertSame([1, 2, 3, 4], $collection->all(), 'the original must not change');
    }

    public function testMergeAndUnique(): void
    {
        $this->assertSame([1, 2, 3, 4], Collection::of([1, 2])->merge([3], new \ArrayIterator([4]))->all());
        $this->assertSame(['a' => 2], Collection::of(['a' => 1])->merge(['a' => 2])->all());
        $this->assertSame([0 => 1, 1 => 2, 3 => 3], Collection::of([1, 2, 2, 3, 1])->unique()->all());
    }

    public function testSort(): void
    {
        $this->assertSame([1 => 1, 2 => 2, 0 => 3], Collection::of([3, 1, 2])->sort()->all());
    }

    public function testSortBy(): void
    {
        $people = Collection::of([
            ['name' => 'Bob', 'age' => 30],
            ['name' => 'Ann', 'age' => 25],
            ['name' => 'Cid', 'age' => 35],
        ]);

        $this->assertSame(['Ann', 'Bob', 'Cid'], $people->sortBy('name')->pluck('name')->all());
        $this->assertSame(['Cid', 'Bob', 'Ann'], $people->sortBy('age', true)->pluck('name')->all());
        $this->assertSame(['Ann', 'Bob', 'Cid'], $people->sortBy(fn(array $p) => $p['age'])->pluck('name')->all());
        $this->assertSame([1, 0, 2], $people->sortBy('age')->keys()->all(), 'keys are kept');
    }

    public function testPluck(): void
    {
        $collection = Collection::of([
            ['id' => 1, 'user' => ['name' => 'Ann']],
            ['id' => 2, 'user' => ['name' => 'Bob']],
            'not an array',
        ]);

        $this->assertSame(['Ann', 'Bob'], $collection->pluck('user.name')->all());
        $this->assertSame([1 => 'Ann', 2 => 'Bob'], $collection->pluck('user.name', 'id')->all());
    }

    public function testGroupBy(): void
    {
        $collection = Collection::of([
            ['type' => 'a', 'n' => 1],
            ['type' => 'b', 'n' => 2],
            ['type' => 'a', 'n' => 3],
        ]);

        $groups = $collection->groupBy('type');

        $this->assertSame(['a', 'b'], $groups->keys()->all());
        $this->assertSame([1, 3], Collection::of($groups->get('a'))->pluck('n')->all());
        $this->assertSame(
            ['odd', 'even'],
            Collection::of([1, 2, 3])->groupBy(fn($n) => $n % 2 ? 'odd' : 'even')->keys()->all(),
        );
    }

    public function testFlatten(): void
    {
        $collection = Collection::of([1, [2, [3, [4]]]]);

        $this->assertSame([1, 2, 3, 4], $collection->flatten()->all());
        $this->assertSame([1, 2, [3, [4]]], $collection->flatten(1)->all());
    }

    public function testStatistics(): void
    {
        $collection = Collection::of([4, 1, 3, 2, 2]);

        $this->assertSame(12, $collection->sum());
        $this->assertSame(2.4, $collection->average());
        $this->assertSame(1, $collection->min());
        $this->assertSame(4, $collection->max());
        $this->assertSame(2, $collection->median());
        $this->assertSame(2.5, Collection::of([4, 1, 2, 3])->median());
    }

    public function testStatisticsOnAColumn(): void
    {
        $collection = Collection::of([
            ['salary' => 10],
            ['salary' => '30'],
            ['salary' => 20],
        ]);

        $this->assertSame(60, $collection->sum('salary'));
        $this->assertSame(20.0, $collection->average('salary'));
        $this->assertSame(10, $collection->min('salary'));
        $this->assertSame(30, $collection->max('salary'));
        $this->assertSame(20, $collection->median('salary'));
    }

    public function testStatisticsOfAnEmptyCollection(): void
    {
        $empty = Collection::of();

        $this->assertSame(0, $empty->sum());
        $this->assertNull($empty->average());
        $this->assertNull($empty->min());
        $this->assertNull($empty->max());
        $this->assertNull($empty->median());
    }

    public function testStatisticsRejectNonNumericValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collection::of([1, 'abc'])->sum();
    }
}
