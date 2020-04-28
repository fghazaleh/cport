<?php


use Asg\Support\Math;

class MathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function test_abs()
    {
        $this->assertEquals(5.3,Math::abs(-5.3)->value());
        $this->assertEquals(6,Math::abs(6)->value());
        $this->assertEquals(4,Math::abs(-4)->value());
    }
    /**
     * @test
     * */
    public function test_math_ceil()
    {
        $this->assertEquals(5,Math::ceil(4.3)->value());
        $this->assertEquals(5,Math::roundUp(4.2)->value());
        $this->assertEquals(-3,Math::ceil(-3.3)->value());
    }
    /**
     * @test
     * */
    public function test_math_floor()
    {
        $this->assertEquals(10,Math::floor(10.3)->value());
        $this->assertEquals(10,Math::roundDown(10.8)->value());
        $this->assertEquals(-7,Math::floor(-6.3)->value());
    }
}