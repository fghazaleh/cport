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
}