<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use FG\Support\Strings\Traits\Charable;

class Str implements \IteratorAggregate
{
    use Charable;

    private $value = '';

    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    public static function fromString(string $value):self
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

    public function trim($char = ' '): self
    {
        return new static(
            trim($this->value, $char)
        );
    }

    public function rtrim($char = ' '): self
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

    public function toUpper(): self
    {
        return new static(
            strtoupper($this->value)
        );
    }

    public function toLower(): self
    {
        return new static(
            strtolower($this->value)
        );
    }

    public function replace(string $search, string $replace): self
    {
        return new static(
            str_replace($search, $replace, $this->value)
        );
    }

    public function subString(int $start, ?int $length = null): self
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

    public function appendAfter(string $value): self
    {
        return new static($this->value . $value);
    }

    public function appendBefore(string $value): self
    {
        return new static($value . $this->value);
    }

    public function stripTags($allows = null): self
    {
        return new static(strip_tags($this->value, $allows));
    }

    public function reverse(): self
    {
        return new static(
            strrev($this->value)
        );
    }

    /* ----------- */

    public function startsWith(string $value): bool
    {
        return ($value !== '' && strncmp($this->value, $value, strlen($value)) === 0);
    }

    public function endsWith(string $value): bool
    {
        return $value !== '' && substr($this->value, -strlen($value)) === $value;
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

    public function chartAt(int $index): Char
    {
        return new Char(
            $this
                ->subString($index, 1)
                ->toString()
        );
    }

    public function chartCodeAt(int $index): int
    {
        return $this->chartAt($index)->code();
    }
}