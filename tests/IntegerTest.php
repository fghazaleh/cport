<?php
/**
 * Created by PhpStorm.
 * User: Ghazaleh
 * Date: 5/13/17
 * Time: 6:43 PM
 */

use Asg\Support\Integer;

class IntegerTest extends \PHPUnit_Framework_TestCase{

    /**
     * @test
     * */
    public function create_instance_from_static(){
        $c = Integer::make(3);
        $this->assertInstanceOf(Integer::class,$c);
    }

    /**
     * @test
     * */
    public function parse_string_to_int(){
        $c = Integer::make('abc');
        $result = $c->value();
        $this->assertInternalType('int',$result);
        $this->assertEquals(0,$result);
    }
    /**
     * @test
     * */
    public function test_math_abs(){
        $c = Integer::make(-4.3);
        $result = $c->abs()->value();
        $this->assertInternalType('float',$result);
        $this->assertEquals(4.3,$result);
    }
    /**
     * @test
     * */
    public function test_math_ceil(){
        $c = Integer::make(4.3);
        $result = $c->ceil()->value();
        $this->assertEquals(5,$result);

        $c = Integer::make(-3.3);
        $result = $c->ceil()->value();
        $this->assertEquals(-3,$result);
    }
}