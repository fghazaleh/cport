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
        $this->assertEquals(12.00,$result);
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
    public function test_math_abs(){
        $c = Number::make(-4.3);
        $result = $c->abs()->value();
        $this->assertInternalType('float',$result);
        $this->assertEquals(4.3,$result);
    }
    /**
     * @test
     * */
    public function test_math_ceil(){
        $c = Number::make(4.3);
        $result = $c->ceil()->value();
        $this->assertEquals(5,$result);

        $c = Number::make(-3.3);
        $result = $c->ceil()->value();
        $this->assertEquals(-3,$result);
    }
    /**
     * @test
     * */
    public function test_math_floor(){
        $c = Number::make(10.3);
        $result = $c->floor()->value();
        $this->assertEquals(10,$result);

        $c = Number::make(-6.3);
        $result = $c->floor()->value();
        $this->assertEquals(-7,$result);
    }
}