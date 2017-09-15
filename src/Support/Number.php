<?php
/**
 * Created by PhpStorm.
 * User: Ghazaleh
 * Date: 5/13/17
 * Time: 6:40 PM
 */

namespace Asg\Support;


class Number
{

    const MAX_INT = PHP_INT_MAX;
    const MIN_INT = -PHP_INT_MAX;

    private $value = 0;
    /**
     * @param mixed $value;
     * */
    function __construct($value = 0){
        $this->value = $this->tryParseToInt($value);
    }

    /**
     * @param mixed;
     * @return $this;
     * */
    public static function make($value = 0){
        return new static($value);
    }


    /**
     * Pow Exponential expression
     * @param int $exp;
     * @return $this;
     * */
    public function pow(int $exp = 1){
        $this->value = pow($this->value,intval($exp));
        return $this;
    }
    /**
     * Average of args numbers;
     * @param int ...args
     * @return $this;
     * */
    public function avg(...$args){
        $numbers = array_map([$this,'tryParseToInt'],$args);
        $this->value = array_sum($numbers)/sizeof($numbers);
        return $this;
    }
    /**
     * Sum of args numbers
     * @param int ...args
     * @return $this;
     * */
    public function sum(...$args){
        $numbers = array_map([$this,'tryParseToInt'],$args);
        $this->value = array_sum($numbers);
        return $this;
    }
    /**
     * Minimum of args numbers
     * @param int ...args
     * @return $this;
     * */
    public function min(...$args){
        $numbers = array_map([$this,'tryParseToInt'],$args);
        $this->value = min($numbers);
        return $this;
    }
    /**
     * Maximum of args numbers
     * @param int ...args
     * @return $this;
     * */
    public function max(...$args){
        $numbers = array_map([$this,'tryParseToInt'],$args);
        $this->value = max($numbers);
        return $this;
    }
    /**
     * @return int|float|double|string;
     * */
    public function value(){
        return $this->value;
    }
    /**
     * Return the converted number to float.
     * @param int $decimals;
     * @return float;
     * */
    public function toFloat(int $decimals = 2):float{
        return number_format($this->value(),$decimals);
    }
    /**
     * Used to convert number as float string.
     * @param int $decimals;
     * @return string;
     * */
    public function toFloatFormat(int $decimals = 2):string {
        return number_format($this->value(),$decimals);
    }

    /**
     * @param mixed $value;
     * @param int $defaultValue;
     * @return mixed;
     * */
    private function tryParseToInt($value,int $defaultValue = 0){
        if ( is_numeric($value) ){
            return $value;
        }
        return intval($value,$defaultValue);
    }
}