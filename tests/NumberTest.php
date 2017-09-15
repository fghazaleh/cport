<?php
/**
 * Created by PhpStorm.
 * User: Ghazaleh
 * Date: 5/13/17
 * Time: 6:43 PM
 */

use Asg\Support\Number;

class NumberTest extends \PHPUnit_Framework_TestCase{

    /**
     * @test
     * */
    public function create_instance_from_static(){
        $c = Number::make(3);
        $this->assertInstanceOf(Number::class,$c);
    }
    /**
     * @test
     * */
    public function convert_number_to_float_format(){
        $c = Number::make(12);
        $result = $c->toFloatFormat();
        $this->assertInternalType('string',$result);
        $this->assertSame('12.00',$result);
    }
    /**
     * @test
     * */
    public function casting_number_to_float(){
        $c = Number::make(12.1234);
        $result = $c->toFloat(2);
        $this->assertInternalType('float',$result);
        $this->assertEquals(12.12,$result);
    }
    /**
     * @test
     * */
    public function parse_string_to_int(){
        $c = Number::make('abc');
        $result = $c->value();
        $this->assertInternalType('int',$result);
        $this->assertEquals(0,$result);
    }
    /**
     * @test
     * */
    public function test_math_pow(){
        $c = Number::make(2);
        $result = $c->pow(4)->value();
        $this->assertInternalType('int',$result);
        $this->assertEquals(16,$result);
    }


    /**
     * @test
     * */
    public function test_math_avg(){
        $this->assertEquals(2,Number::make()->avg(1,2,3)->value());
    }
    /**
     * @test
     * */
    public function test_math_sum(){
        $this->assertEquals(6,Number::make()->sum(1,2,3)->value());
    }
    /**
     * @test
     * */
    public function test_math_min_max(){
        $this->assertEquals(-5,Number::make()->min(1,2,3,-5,-2.4)->value());
        $this->assertEquals(80,Number::make()->max(80,1,2,3)->value());
    }
}