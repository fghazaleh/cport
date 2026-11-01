<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

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
        $arr->add(3);
        $this->assertSame([1, 2, 3], $arr->all());
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
        $arr->map(function ($item) {
            return $item * 2;
        });
        $this->assertSame([2, 4, 6], $arr->all());
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
            ]
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
        $arr->sort();
        $this->assertSame(['Ben' => 32, 'Peter' => 43, 'Joe' => 52], $arr->all());

        $arr->sort(Arr::SORT_DESC);
        $this->assertSame(['Joe' => 52, 'Peter' => 43, 'Ben' => 32], $arr->all());

        $arr = new Arr(['temp2.txt', 'temp10.txt', 'temp1.txt']);

        $arr->sort(Arr::SORT_NAT);
        $this->assertSame([2 => 'temp1.txt', 0 => 'temp2.txt', 1 => 'temp10.txt'], $arr->all());
    }

    public function testingArrWhereSort(): void
    {
        $arr = Arr::createFrom(['a' => 4, 'b' => 2, 'c' => 8, 'd' => 6]);

        $arr->whereSort(function ($item1, $item2) {
            return ($item1 < $item2) ? -1 : 1;
        });

        $this->assertSame(['b' => 2, 'a' => 4, 'd' => 6, 'c' => 8], $arr->all());
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
            'green' => 'b'
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
}
