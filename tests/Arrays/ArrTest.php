<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

use FG\Support\Exceptions\ImmutableException;
use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function testingCreateNewArrInstance(): void
    {
        $arr = new Arr([1, 2, 3]);
        $this->assertInstanceOf(Arr::class, $arr);
    }

    public function testingCreateArrInstanceFromStatic(): void
    {
        $arr = Arr::createFrom([1, 2, 3]);
        $this->assertInstanceOf(Arr::class, $arr);
    }

    public function testingArraySlice(): void
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);

        $result = $arr->slice(3, 2);
        $this->assertSame([4, 5], $result->all());
    }

    public function testingAddToArray(): void
    {
        $arr = new Arr([1, 2]);
        $result = $arr->add(3);
        $this->assertSame([1, 2, 3], $result->all());
        $this->assertSame([1, 2], $arr->all(), 'Arr must stay immutable');
    }

    public function testingMergeArrays(): void
    {
        $arr = new Arr([1, 2]);
        $arr = $arr->merge([3, 4]);
        $this->assertSame([1, 2, 3, 4], $arr->all());
    }

    public function testingArrMap(): void
    {
        $arr = new Arr([1, 2, 3]);
        $result = $arr->map(fn($item) => $item * 2);
        $this->assertSame([2, 4, 6], $result->all());
        $this->assertSame([1, 2, 3], $arr->all());
    }

    public function testingArrEach(): void
    {
        $arr = new Arr([1, 2, 3]);
        $result = [];
        $arr->each(function ($item) use (&$result) {
            if ($item == 2) {
                return false;
            }
            $result[] = $item;
        });
        $this->assertSame([1], $result);
    }

    public function testingArrWhere(): void
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);
        $result = $arr->where(function ($item) {
            return $item % 2 === 0;
        })->all();

        $this->assertEquals([1 => 2, 3 => 4, 5 => 6], $result);
    }

    public function testingArrFirst(): void
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);
        $result = $arr->first(function ($item) {
            return $item % 2 === 0;
        });

        $this->assertSame(2, $result);
    }

    public function testingArrLast(): void
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);
        $result = $arr->last(function ($item) {
            return $item % 2 !== 0;
        });

        $this->assertSame(5, $result);
    }

    public function testingCount(): void
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);

        $this->assertSame(6, $arr->count());
    }

    public function testingColumn(): void
    {
        $array = [
            [
                'id' => 5698,
                'first_name' => 'Peter',
                'last_name' => 'Griffin',
            ],
            [
                'id' => 4767,
                'first_name' => 'Ben',
                'last_name' => 'Smith',
            ],
        ];
        $arr = new Arr($array);

        $arr = $arr->column('last_name');
        $this->assertSame([0 => 'Griffin', 1 => 'Smith'], $arr->all());
    }

    public function testingCombine(): void
    {
        $age = [22, 43, 52];
        $arr = new Arr(['Peter', 'Ben', 'Joe']);

        $arr = $arr->combine($age);
        $this->assertSame(['Peter' => 22, 'Ben' => 43, 'Joe' => 52], $arr->all());
    }

    public function testingArrSorting(): void
    {
        $arr = new Arr(['Peter' => 43, 'Ben' => 32, 'Joe' => 52]);
        $this->assertSame(['Ben' => 32, 'Peter' => 43, 'Joe' => 52], $arr->sort()->all());
        $this->assertSame(['Joe' => 52, 'Peter' => 43, 'Ben' => 32], $arr->sort(SortOrder::Desc)->all());
        $this->assertSame(['Peter' => 43, 'Ben' => 32, 'Joe' => 52], $arr->all());

        $arr = new Arr(['temp2.txt', 'temp10.txt', 'temp1.txt']);

        $this->assertSame([2 => 'temp1.txt', 0 => 'temp2.txt', 1 => 'temp10.txt'], $arr->sort(SortOrder::Natural)->all());
    }

    public function testingArrWhereSort(): void
    {
        $arr = Arr::createFrom(['a' => 4, 'b' => 2, 'c' => 8, 'd' => 6]);

        $result = $arr->whereSort(fn($item1, $item2) => ($item1 < $item2) ? -1 : 1);

        $this->assertSame(['b' => 2, 'a' => 4, 'd' => 6, 'c' => 8], $result->all());
    }

    public function testingKeys(): void
    {
        $arr = Arr::createFrom(['a' => 4, 'b' => 2, 'c' => 8, 'd' => 6]);

        $keys = $arr->keys();

        $this->assertSame(['a', 'b', 'c', 'd'], $keys->all());
    }

    public function testingKeyExists(): void
    {
        $arr = Arr::createFrom(['key1' => 'Franco', 'key3' => 'Foo']);

        $this->assertTrue($arr->keyExists('key1'));
        $this->assertFalse($arr->keyExists('key2'));
    }

    public function testingArrDiff(): void
    {
        $arr = new Arr(['a' => 'red', 'b' => 'green', 'c' => 'blue', 'd' => 'yellow']);
        $arr = $arr->diff(['e' => 'red', 'f' => 'green', 'g' => 'blue']);

        $this->assertSame(['d' => 'yellow'], $arr->all());
    }

    public function testingArrFlip(): void
    {
        $arr = new Arr(['a' => 'red', 'b' => 'green']);
        $this->assertSame([
            'red' => 'a',
            'green' => 'b',
        ], $arr->flip()->all());
    }

    public function testingArrSum(): void
    {
        $arr1 = new Arr([1, 2, 3]);
        $arr2 = new Arr([
            ['name' => 'name 1', 'salary' => 22],
            ['name' => 'name 2', 'salary' => 44],
        ]);
        $this->assertSame(6, $arr1->sum());
        $this->assertSame(66, $arr2->sum('salary'));
    }

    public function testingArrChunk(): void
    {
        $arr = new Arr(['Volvo', 'BMW', 'Toyota', 'Honda', 'Mercedes', 'Opel']);

        $result = $arr->chunk(2);
        $this->assertCount(3, $result);
        $this->assertSame([
            ['Volvo', 'BMW'],
            ['Toyota', 'Honda'],
            ['Mercedes', 'Opel'],
        ], $result->all());
    }

    public function testingArrPad(): void
    {
        $arr = new Arr([12, 10, 9]);

        $result = $arr->pad(6, 0);
        $this->assertCount(6, $result);
        $this->assertSame([12, 10, 9, 0, 0, 0], $result->all());
    }

    public function testingArrFill(): void
    {
        $arr = Arr::fill('franco', 4);

        $this->assertCount(4, $arr);
        $this->assertSame(['franco', 'franco', 'franco', 'franco'], $arr->all());
    }

    public function testingArrRand(): void
    {
        $arr = Arr::createFrom([12, 10, 9, 4, 5]);

        $result = $arr->rand(2);
        $this->assertCount(2, $result);
    }

    public function testingOffsetAccessIsReadOnly(): void
    {
        $arr = new Arr(['a' => 1]);

        $this->assertSame(1, $arr['a']);
        $this->assertNull($arr['missing']);
        $this->assertTrue(isset($arr['a']));

        $this->expectException(ImmutableException::class);
        $arr['b'] = 2;
    }

    public function testingUnsetIsRejected(): void
    {
        $arr = new Arr(['a' => 1]);

        $this->expectException(ImmutableException::class);
        unset($arr['a']);
    }

    public function testingIteratingAnArr(): void
    {
        $result = [];
        foreach (new Arr(['a' => 1, 'b' => 2]) as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testingJsonRoundTrip(): void
    {
        $arr = new Arr(['a' => 1, 'b' => [2, 3]]);

        $this->assertSame('{"a":1,"b":[2,3]}', $arr->toJson());
        $this->assertSame('{"a":1,"b":[2,3]}', json_encode($arr));
        $this->assertSame($arr->all(), Arr::fromJson($arr->toJson())->all());
    }

    public function testingFromInvalidJsonThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Arr::fromJson('{not json');
    }

    public function testingFromScalarJsonThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Arr::fromJson('42');
    }

    public function testingFilter(): void
    {
        $arr = new Arr([1, 0, 2, null, 3]);

        $this->assertSame([0 => 1, 2 => 2, 4 => 3], $arr->filter()->all());
        $this->assertSame([2 => 2, 4 => 3], $arr->filter(fn($i) => $i > 1)->all());
    }

    public function testingReduce(): void
    {
        $this->assertSame(10, (new Arr([1, 2, 3, 4]))->reduce(fn($carry, $i) => $carry + $i, 0));
    }

    public function testingUniqueAndReverseAndValues(): void
    {
        $arr = new Arr([1, 2, 2, 3, 1]);

        $this->assertSame([0 => 1, 1 => 2, 3 => 3], $arr->unique()->all());
        $this->assertSame([1, 3, 2, 2, 1], $arr->reverse()->all());
        $this->assertSame([1, 2, 3], $arr->unique()->values()->all());
    }

    public function testingPluck(): void
    {
        $arr = new Arr([
            ['id' => 1, 'user' => ['name' => 'Ann']],
            ['id' => 2, 'user' => ['name' => 'Bob']],
        ]);

        $this->assertSame(['Ann', 'Bob'], $arr->pluck('user.name')->all());
        $this->assertSame([1 => 'Ann', 2 => 'Bob'], $arr->pluck('user.name', 'id')->all());
    }

    public function testingGroupBy(): void
    {
        $arr = new Arr([
            ['type' => 'a', 'n' => 1],
            ['type' => 'b', 'n' => 2],
            ['type' => 'a', 'n' => 3],
        ]);

        $groups = $arr->groupBy('type');

        $this->assertSame(['a', 'b'], $groups->keys()->all());
        $this->assertInstanceOf(Arr::class, $groups['a']);
        $this->assertSame([1, 3], $groups['a']->pluck('n')->all());
        $this->assertSame(['odd', 'even'], $arr->pluck('n')->groupBy(fn($n) => $n % 2 ? 'odd' : 'even')->keys()->all());
    }

    public function testingFlatten(): void
    {
        $arr = new Arr([1, [2, [3, [4]]]]);

        $this->assertSame([1, 2, 3, 4], $arr->flatten()->all());
        $this->assertSame([1, 2, [3, [4]]], $arr->flatten(1)->all());
    }

    public function testingDotNotation(): void
    {
        $arr = new Arr(['user' => ['address' => ['city' => 'Rome']], 'a.b' => 'literal']);

        $this->assertSame('Rome', $arr->get('user.address.city'));
        $this->assertSame('literal', $arr->get('a.b'));
        $this->assertSame('x', $arr->get('user.address.zip', 'x'));
        $this->assertTrue($arr->has('user.address'));
        $this->assertFalse($arr->has('user.phone'));

        $updated = $arr->set('user.address.zip', '00100')->set('new.key', 1);
        $this->assertSame('00100', $updated->get('user.address.zip'));
        $this->assertSame(1, $updated->get('new.key'));
        $this->assertFalse($arr->has('new.key'), 'Original must be unchanged');
    }

    public function testingExcept(): void
    {
        $this->assertSame(['b' => 2], (new Arr(['a' => 1, 'b' => 2, 'c' => 3]))->except('a', 'c')->all());
    }

    public function testingContains(): void
    {
        $arr = new Arr([1, 2, '3']);

        $this->assertTrue($arr->contains(2));
        $this->assertTrue($arr->contains(3));
        $this->assertFalse($arr->contains(3, true));
        $this->assertTrue($arr->contains(fn($i) => $i > 1));
        $this->assertFalse($arr->contains(fn($i) => $i > 5));
    }

    public function testingIsEmpty(): void
    {
        $this->assertTrue((new Arr())->isEmpty());
        $this->assertTrue((new Arr([1]))->isNotEmpty());
    }

    public function testingFirstAndLastWithoutCallbackAndDefault(): void
    {
        $arr = new Arr([1, 2, 3]);

        $this->assertSame(1, $arr->first());
        $this->assertSame(3, $arr->last());
        $this->assertSame('none', $arr->first(fn($i) => $i > 5, 'none'));
        $this->assertNull((new Arr())->last());
    }

    public function testingCombineWithDifferentLengthsThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Arr(['a', 'b']))->combine([1]);
    }

    public function testingChunkWithInvalidSizeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Arr([1, 2]))->chunk(0);
    }

    public function testingRandWithInvalidSizeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Arr([1, 2]))->rand(5);
    }

    public function testingSortOrderEnumValues(): void
    {
        $this->assertSame('ASC', SortOrder::Asc->value);
        $this->assertSame(SortOrder::Natural, SortOrder::from('NAT'));
    }

    public function testingStatistics(): void
    {
        $arr = new Arr([4, 1, 3, 2, 2]);

        $this->assertSame(2.4, $arr->avg());
        $this->assertSame(1, $arr->min());
        $this->assertSame(4, $arr->max());
        $this->assertSame(2, $arr->median());
        $this->assertSame([2], $arr->mode()->all());
        $this->assertSame(3.0, $arr->percentile(75));
    }

    public function testingStatisticsOnAColumn(): void
    {
        $arr = new Arr([
            ['name' => 'a', 'salary' => 10],
            ['name' => 'b', 'salary' => 30],
            ['name' => 'c', 'salary' => 20],
        ]);

        $this->assertSame(20.0, $arr->avg('salary'));
        $this->assertSame(10, $arr->min('salary'));
        $this->assertSame(30, $arr->max('salary'));
        $this->assertSame(20, $arr->median('salary'));
        $this->assertSame([10, 20, 30], $arr->mode('salary')->all());
    }

    public function testingStatisticsOfAnEmptyArrIsNull(): void
    {
        $arr = new Arr();

        $this->assertNull($arr->avg());
        $this->assertNull($arr->min());
        $this->assertNull($arr->max());
        $this->assertNull($arr->median());
        $this->assertNull($arr->percentile(50));
        $this->assertTrue($arr->mode()->isEmpty());
    }

    public function testingPercentileOutOfRangeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Arr([1, 2]))->percentile(120);
    }

    public function testingCountBy(): void
    {
        $this->assertSame(['a' => 2, 'b' => 1], (new Arr(['a', 'b', 'a']))->countBy()->all());
        $this->assertSame([1 => 2, 0 => 1], (new Arr([1, 2, 3]))->countBy(fn($i) => $i % 2)->all());
        $this->assertSame(['odd' => 2, 'even' => 1], (new Arr([1, 2, 3]))->countBy(fn($i) => $i % 2 ? 'odd' : 'even')->all());
    }
}
