<?php

declare(strict_types=1);

namespace FG\Support;

/**
 * Immutable sequence of the characters (UTF-8 code points) of a string.
 *
 * @implements \IteratorAggregate<int, string>
 */
final readonly class Chars implements \Countable, \IteratorAggregate, \JsonSerializable, \Stringable
{
    /**
     * @param list<string> $chars
     */
    private function __construct(private array $chars) {}

    public static function of(string|\Stringable $text = ''): self
    {
        return new self(Str::split((string) $text));
    }

    /**
     * Builds the sequence from Unicode code points.
     *
     * @param list<int> $codePoints
     * @throws Exceptions\InvalidArgumentException when a value is not a valid code point
     */
    public static function fromCodes(array $codePoints): self
    {
        return new self(array_map(Str::chr(...), array_values($codePoints)));
    }

    public function count(): int
    {
        return \count($this->chars);
    }

    public function isEmpty(): bool
    {
        return $this->chars === [];
    }

    /**
     * The character at $index (negative counts from the end), or null when out of range.
     */
    public function at(int $index): ?string
    {
        return $this->chars[$index < 0 ? \count($this->chars) + $index : $index] ?? null;
    }

    public function first(): ?string
    {
        return $this->chars[0] ?? null;
    }

    public function last(): ?string
    {
        return $this->chars === [] ? null : $this->chars[array_key_last($this->chars)];
    }

    public function contains(string $char): bool
    {
        return \in_array($char, $this->chars, true);
    }

    /**
     * @return list<string>
     */
    public function toArray(): array
    {
        return $this->chars;
    }

    /**
     * The Unicode code point of every character.
     *
     * @return list<int>
     */
    public function codes(): array
    {
        return array_map(Str::ord(...), $this->chars);
    }

    /**
     * How many times each character occurs (digit characters become integer keys, as in any PHP array).
     *
     * @return array<array-key, int>
     */
    public function frequency(): array
    {
        return array_count_values($this->chars);
    }

    // ---- transforming ----------------------------------------------------------------

    /**
     * Keeps the characters for which the callback returns true. Without a callback, falsy characters ("0") are removed.
     */
    public function filter(?callable $callback = null): self
    {
        return new self(array_values(Arr::filter($this->chars, $callback)));
    }

    /**
     * Removes the characters for which the callback returns true.
     */
    public function reject(callable $callback): self
    {
        return new self(array_values(Arr::filter($this->chars, static fn(string $char): bool => !$callback($char))));
    }

    /**
     * Replaces every character with the string the callback returns (a longer string becomes several characters).
     */
    public function map(callable $callback): self
    {
        return self::of(implode('', array_map(static fn(string $char): string => (string) $callback($char), $this->chars)));
    }

    public function reverse(): self
    {
        return new self(Arr::reverse($this->chars));
    }

    public function unique(): self
    {
        return new self(array_values(Arr::unique($this->chars, SORT_STRING)));
    }

    /**
     * Sorts by code point.
     */
    public function sort(bool $descending = false): self
    {
        return new self($descending ? Arr::rsort($this->chars, SORT_STRING) : Arr::sort($this->chars, SORT_STRING));
    }

    /**
     * The first $limit characters, or the last ones when $limit is negative.
     */
    public function take(int $limit): self
    {
        return new self($limit >= 0 ? Arr::slice($this->chars, 0, $limit) : Arr::slice($this->chars, $limit));
    }

    public function skip(int $count): self
    {
        return new self(Arr::slice($this->chars, $count));
    }

    // ---- character classes ------------------------------------------------------------------

    public function letters(): self
    {
        return $this->matching('/^\p{L}$/u');
    }

    public function digits(): self
    {
        return $this->matching('/^\p{Nd}$/u');
    }

    public function spaces(): self
    {
        return $this->matching('/^\s$/u');
    }

    public function uppercase(): self
    {
        return $this->matching('/^\p{Lu}$/u');
    }

    public function lowercase(): self
    {
        return $this->matching('/^\p{Ll}$/u');
    }

    public function punctuation(): self
    {
        return $this->matching('/^\p{P}$/u');
    }

    // ---- converting ---------------------------------------------------------------------------

    public function toString(): string
    {
        return implode('', $this->chars);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return list<string>
     */
    public function jsonSerialize(): array
    {
        return $this->chars;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->chars);
    }

    private function matching(string $pattern): self
    {
        return new self(array_values(array_filter(
            $this->chars,
            static fn(string $char): bool => preg_match($pattern, $char) === 1,
        )));
    }
}
