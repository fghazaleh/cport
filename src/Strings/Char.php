<?php

declare(strict_types=1);

namespace FG\Support\Strings;

class Char
{
    public function __construct(private readonly string $char) {}

    public function toString(): string
    {
        return $this->char;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Unicode code point of the character.
     */
    public function code(): int
    {
        return mb_ord($this->char);
    }

    /**
     * Converts the character's bytes to hexadecimal values
     */
    public function toHex(): string
    {
        return bin2hex($this->char);
    }

    public static function fromCode(int $codePoint): static
    {
        return new static(mb_chr($codePoint));
    }
}
