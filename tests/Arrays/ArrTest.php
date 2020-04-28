<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    /**
     * @test
     */
    public function testingCreateNewArrInstance()
    {
        $arr = new Arr([1, 2, 3]);
        $this->assertInstanceOf(Arr::class, $arr);
    }

    /**
     * @test
     */
    public function testingCreateArrInstanceFromStatic()
    {
        $arr = Arr::createFrom([1, 2, 3]);
        $this->assertInstanceOf(Arr::class, $arr);
    }

    /**
     * @test
     */
    public function testingArraySlice()
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);

        $result = $arr->slice(3, 2);
        $this->assertSame([4, 5], $result->all());
    }

    /**
     * @test
     */
    public function testingAddToArray()
    {
        $arr = new Arr([1, 2]);
        $arr->add(3);
        $this->assertSame([1, 2, 3], $arr->all());
    }

    /**
     * @test
     */
    public function testingMergeArrays()
    {
        $arr = new Arr([1, 2]);
        $arr->merge([3, 4]);
        $this->assertSame([1, 2, 3, 4], $arr->all());
    }

    /**
     * @test
     */
    public function testingArrMap()
    {
        $arr = new Arr([1, 2, 3]);
        $arr->map(function ($item) {
            return $item * 2;
        });
        $this->assertSame([2, 4, 6], $arr->all());
    }

    /**
     * @test
     */
    public function testingArrEach()
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

    /**
     * @test
     */
    public function testingArrWhere()
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);
        $result = $arr->where(function ($item) {
            return $item % 2 === 0;
        })->all();

        $this->assertSame([2, 4, 6], $result);
    }

    /**
     * @test
     */
    public function testingArrFirst()
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);
        $result = $arr->first(function ($item) {
            return $item % 2 === 0;
        });

        $this->assertSame(2, $result);
    }

    /**
     * @test
     */
    public function testingArrLast()
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);
        $result = $arr->last(function ($item) {
            return $item % 2 !== 0;
        });

        $this->assertSame(5, $result);
    }

    /**
     * @test
     */
    public function testingCount()
    {
        $arr = new Arr([1, 2, 3, 4, 5, 6]);

        $this->assertSame(6, $arr->count());
    }

    /**
     * @test
     */
    public function testingColumn()
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

        $arr->column('last_name');
        $this->assertSame([0 => 'Griffin', 1 => 'Smith'], $arr->all());
    }

    /**
     * @test
     */
    public function testingCombine()
    {
        $age = [22, 43, 52];
        $arr = new Arr(['Peter', 'Ben', 'Joe']);

        $arr->combine($age);
        $this->assertSame(['Peter' => 22, 'Ben' => 43, 'Joe' => 52], $arr->all());
    }
}