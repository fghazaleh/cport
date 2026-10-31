<?php

declare(strict_types=1);

namespace FG\Support\Strings;

class Char
{
    public function __construct(private readonly string $char)
    {
    }

    public function toString(): string
    {
        return $this->char;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function code(): int
    {
        return ord($this->char);
    }

    /**
     * Converts a string of ASCII characters to hexadecimal values
     *
     * @return string
     */
    public function toHex(): string
    {
        return bin2hex($this->char);
    }

    public static function fromCode(int $byteValue): static
    {
        return new static(chr($byteValue));
    }
}
