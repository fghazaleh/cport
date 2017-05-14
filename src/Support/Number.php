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
    public static function make($value){
        return new static($value);
    }
    /**
     * Absolute value
     * @return $this;
     * */
    public function abs(){
        $this->value = abs($this->value);
        return $this;
    }
    /**
     * Round fractions up
     * @return $this;
     * */
    public function ceil(){
        $this->value = ceil($this->value);
        return $this;
    }
    /**
     * Round fractions down
     * @return $this;
     * */
    public function floor(){
        $this->value = floor($this->value);
        return $this;
    }
    /**
     * Pow Exponential expression
     * @param int $exp;
     * @return $this;
     * */
    public function pow($exp = 1){
        $this->value = pow($this->value,intval($exp));
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
    public function toFloat($decimals = 2){
        return (float)number_format($this->value(),intval($decimals));
    }
    /**
     * Used to convert number as float string.
     * @param int $decimals;
     * @return string;
     * */
    public function toFloatFormat($decimals = 2){
        return number_format($this->value(),intval($decimals));
    }

    /**
     * @param mixed $value;
     * @param int $defaultValue;
     * @return int;
     * */
    private function tryParseToInt($value,$defaultValue = 0){
        if ( is_numeric($value) ){
            return $value;
        }
        return intval($value,$defaultValue);
    }
}