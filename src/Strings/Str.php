<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use FG\Support\Arrays\Arr;
use FG\Support\Strings\Traits\Charable;

class Str implements \IteratorAggregate
{
    use Charable;

    public function __construct(private readonly string $value = '')
    {
    }

    public static function fromString(string $value): static
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

    public function trim(string $char = ' '): static
    {
        return new static(
            trim($this->value, $char)
        );
    }

    public function rtrim(string $char = ' '): static
    {
        return new static(
            rtrim($this->value, $char)
        );
    }

    public function ltrim(string $char = ' '): self
    {
        return new static(
            ltrim($this->value, $char)
        );
    }

    public function length(): int
    {
        return strlen($this->value);
    }

    public function toUpper(): static
    {
        return new static(
            strtoupper($this->value)
        );
    }

    public function toLower(): static
    {
        return new static(
            strtolower($this->value)
        );
    }

    public function toLowerFirst(): static
    {
        return new static(
            lcfirst($this->value)
        );
    }

    public function toUpperFirst(): static
    {
        return new static(
            ucwords($this->value)
        );
    }

    /**
     */
    public function replace(array|string $search, array|string $replace): static
    {
        return new static(
            str_replace($search, $replace, $this->value)
        );
    }

    public function subString(int $start, ?int $length = null): static
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

    public function appendAfter(string $value): static
    {
        return new static($this->value . $value);
    }

    public function appendBefore(string $value): static
    {
        return new static($value . $this->value);
    }

    public function stripTags(array|string|null $allows = null): static
    {
        return new static(strip_tags($this->value, $allows));
    }

    public function reverse(): static
    {
        return new static(
            strrev($this->value)
        );
    }

    public function pad(int $length, string $padString = ' ', int $direction = STR_PAD_BOTH): static
    {
        return new static(
            str_pad($this->value, $length, $padString, $direction)
        );
    }

    public function parseQueryString(): Arr
    {
        parse_str($this->value, $output);

        return new Arr($output);
    }

    /**
     * Convert a value to camel case.
     */
    public function camel(): static
    {
        return $this->studly()->toLowerFirst();
    }

    /**
     * Convert a value to studly caps case.
     */
    public function studly(): static
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
        return $value !== '' && str_starts_with($this->value, $value);
    }

    public function endsWith(string $value): bool
    {
        return $value !== '' && str_ends_with($this->value, $value);
    }

    public function contains(string $find): bool
    {
        return str_contains($this->value, $find);
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
