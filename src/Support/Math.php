<?php
/**
 * Created by PhpStorm.
 * User: abed
 * Date: 9/15/17
 * Time: 6:37 PM
 */

namespace Asg\Support;

class Math
{
    /**
     * Absolute value
     * @param $value
     * @return \Asg\Support\Number|Number
     */
    public static function abs($value):Number
    {
        return new Number(abs($value));
    }

    /**
     * Round fractions up
     * @param float $value
     * @return \Asg\Support\Number|Number
     */
    public static function ceil(float $value):Number
    {
        return new Number(ceil($value));
    }

    /**
     * Round fractions up
     * @param float $value
     * @return \Asg\Support\Number|Number
     */
    public static function roundUp(float $value):Number
    {
        return static::ceil($value);
    }

    /**
     * Round fractions down
     * @param float $value
     * @return \Asg\Support\Number|Number
     */
    public static function floor(float $value):Number
    {
        return new Number(floor($value));
    }

    /**
     * @Alias of floor method, used to round fractions down
     * @param float $value
     * @return \Asg\Support\Number|Number
     */
    public static function roundDown(float $value):Number
    {
        return static::floor($value);
    }
}