<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use FG\Support\Arrays\Arr;
use FG\Support\Strings\Traits\Charable;

class Str implements \IteratorAggregate
{
    use Charable;

    private $value = '';

    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    public static function fromString(string $value): Str
    {
        return new static($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function trim($char = ' '): Str
    {
        return new static(
            trim($this->value, $char)
        );
    }

    public function rtrim($char = ' '): Str
    {
        return new static(
            rtrim($this->value, $char)
        );
    }

    public function ltrim($char = ' '): self
    {
        return new static(
            ltrim($this->value, $char)
        );
    }

    public function length(): int
    {
        return strlen($this->value);
    }

    public function toUpper(): Str
    {
        return new static(
            strtoupper($this->value)
        );
    }

    public function toLower(): Str
    {
        return new static(
            strtolower($this->value)
        );
    }

    public function toLowerFirst(): Str
    {
        return new static(
            lcfirst($this->value)
        );
    }

    public function toUpperFirst(): Str
    {
        return new static(
            ucwords($this->value)
        );
    }

    /**
     * @param array|string $search
     * @param array|string $replace
     * @return Str
     */
    public function replace($search, $replace): Str
    {
        return new static(
            str_replace($search, $replace, $this->value)
        );
    }

    public function subString(int $start, ?int $length = null): Str
    {
        if ($length === null) {
            return new static(
                substr($this->value, $start)
            );
        }
        return new static(
            substr($this->value, $start, $length)
        );
    }

    public function appendAfter(string $value): Str
    {
        return new static($this->value . $value);
    }

    public function appendBefore(string $value): Str
    {
        return new static($value . $this->value);
    }

    public function stripTags($allows = null): Str
    {
        return new static(strip_tags($this->value, $allows));
    }

    public function reverse(): Str
    {
        return new static(
            strrev($this->value)
        );
    }

    public function pad(int $length, string $padString = ' ', int $direction = STR_PAD_BOTH): Str
    {
        return new static(
            str_pad($this->value, $length, $padString, $direction)
        );
    }

    public function parseQueryString(): Arr
    {
        parse_str($this->value, $output);

        return new Arr(is_array($output) ? $output : []);
    }

    /**
     * Convert a value to camel case.
     *
     * @return Str
     */
    public function camel(): Str
    {
        return $this->studly()->toLowerFirst();
    }

    /**
     * Convert a value to studly caps case.
     *
     * @return Str
     */
    public function studly(): Str
    {
        return $this->replace(['-', '_'], ' ')
            ->toUpperFirst()
            ->replace(' ', '');
    }

    public function toMd5(string $salt = ''): string
    {
        return md5($this->value . $salt);
    }

    public function startsWith(string $value): bool
    {
        return ($value !== '' && strncmp($this->value, $value, strlen($value)) === 0);
    }

    public function endsWith(string $value): bool
    {
        return $value !== '' && substr($this->value, -strlen($value)) === $value;
    }

    public function contains(string $find): bool
    {
        return strpos($this->value, $find) !== false;
    }

    public function any(string $find): bool
    {
        return stripos($this->value, $find) !== false;
    }

    public function indexOf(string $find, int $offset = 0): int
    {
        $pos = strpos($this->value, $find, $offset);
        if ($pos === false) {
            return -1;
        }

        return $pos;
    }

    public function lastIndexOf(string $find, int $offset = 0): int
    {
        $pos = strrpos($this->value, $find, $offset);
        if ($pos === false) {
            return -1;
        }

        return $pos;
    }

    public function charAt(int $index): Char
    {
        return new Char(
            $this
                ->subString($index, 1)
                ->toString()
        );
    }

    public function charCodeAt(int $index): int
    {
        return $this->charAt($index)->code();
    }
}
