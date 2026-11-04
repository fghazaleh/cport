<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use FG\Support\Arrays\Arr;
use FG\Support\Exceptions\InvalidArgumentException;
use FG\Support\Strings\Traits\Charable;

class Str implements \IteratorAggregate
{
    use Charable;

    public function __construct(private readonly string $value = '') {}

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
            trim($this->value, $char),
        );
    }

    public function rtrim(string $char = ' '): static
    {
        return new static(
            rtrim($this->value, $char),
        );
    }

    public function ltrim(string $char = ' '): self
    {
        return new static(
            ltrim($this->value, $char),
        );
    }

    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function toUpper(): static
    {
        return new static(
            mb_strtoupper($this->value),
        );
    }

    public function toLower(): static
    {
        return new static(
            mb_strtolower($this->value),
        );
    }

    public function toLowerFirst(): static
    {
        return new static(
            mb_strtolower(mb_substr($this->value, 0, 1)) . mb_substr($this->value, 1),
        );
    }

    public function toUpperFirst(): static
    {
        return new static(
            preg_replace_callback('/(?<!\S)\S/u', static fn(array $m): string => mb_strtoupper($m[0]), $this->value),
        );
    }

    /**
     */
    public function replace(array|string $search, array|string $replace): static
    {
        return new static(
            str_replace($search, $replace, $this->value),
        );
    }

    public function subString(int $start, ?int $length = null): static
    {
        if ($length === null) {
            return new static(
                mb_substr($this->value, $start),
            );
        }
        return new static(
            mb_substr($this->value, $start, $length),
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
            implode('', array_reverse(mb_str_split($this->value))),
        );
    }

    public function pad(int $length, string $padString = ' ', int $direction = STR_PAD_BOTH): static
    {
        return new static(
            mb_str_pad($this->value, $length, $padString, $direction),
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
        return mb_stripos($this->value, $find) !== false;
    }

    public function indexOf(string $find, int $offset = 0): int
    {
        $pos = mb_strpos($this->value, $find, $offset);
        if ($pos === false) {
            return -1;
        }

        return $pos;
    }

    public function lastIndexOf(string $find, int $offset = 0): int
    {
        $pos = mb_strrpos($this->value, $find, $offset);
        if ($pos === false) {
            return -1;
        }

        return $pos;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Splits the string into an Arr using a delimiter.
     *
     * @throws InvalidArgumentException when the delimiter is empty
     */
    public function explode(string $delimiter, int $limit = PHP_INT_MAX): Arr
    {
        if ($delimiter === '') {
            throw new InvalidArgumentException('The delimiter cannot be an empty string.');
        }

        return new Arr(explode($delimiter, $this->value, $limit));
    }

    public function repeat(int $times): static
    {
        if ($times < 0) {
            throw new InvalidArgumentException('Times cannot be negative.');
        }

        return new static(str_repeat($this->value, $times));
    }

    /**
     * Truncates to $limit characters, appending $end when truncated.
     */
    public function limit(int $limit, string $end = '...'): static
    {
        if (mb_strlen($this->value) <= $limit) {
            return $this;
        }

        return new static(rtrim(mb_substr($this->value, 0, $limit)) . $end);
    }

    /**
     * Converts the string to title case ("hello world" => "Hello World").
     */
    public function title(): static
    {
        return new static(mb_convert_case($this->value, MB_CASE_TITLE));
    }

    public function wordCount(): int
    {
        return \count(preg_split('/\s+/u', trim($this->value), -1, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Converts to snake_case ("fooBar baz" => "foo_bar_baz").
     */
    public function snake(string $delimiter = '_'): static
    {
        return new static(mb_strtolower($this->splitWords($delimiter)));
    }

    /**
     * Converts to kebab-case ("fooBar baz" => "foo-bar-baz").
     */
    public function kebab(): static
    {
        return $this->snake('-');
    }

    /**
     * Makes a URL friendly, lowercase slug. Unicode letters and digits are kept.
     */
    public function slug(string $separator = '-'): static
    {
        return new static(mb_strtolower(
            trim((string) preg_replace('/[^\p{L}\p{N}]+/u', $separator, $this->value), $separator),
        ));
    }

    /**
     * The part of the string before the first occurrence of $search.
     */
    public function before(string $search): static
    {
        if ($search === '') {
            return $this;
        }
        $pos = mb_strpos($this->value, $search);

        return $pos === false ? $this : new static(mb_substr($this->value, 0, $pos));
    }

    /**
     * The part of the string after the first occurrence of $search.
     */
    public function after(string $search): static
    {
        if ($search === '') {
            return $this;
        }
        $pos = mb_strpos($this->value, $search);

        return $pos === false ? $this : new static(mb_substr($this->value, $pos + mb_strlen($search)));
    }

    /**
     * The part of the string between two values.
     */
    public function between(string $from, string $to): static
    {
        if ($from === '' || $to === '') {
            return $this;
        }

        return $this->after($from)->beforeLast($to);
    }

    /**
     * The part of the string before the last occurrence of $search.
     */
    public function beforeLast(string $search): static
    {
        if ($search === '') {
            return $this;
        }
        $pos = mb_strrpos($this->value, $search);

        return $pos === false ? $this : new static(mb_substr($this->value, 0, $pos));
    }

    private function splitWords(string $delimiter): string
    {
        $value = (string) preg_replace('/(?<=[\p{Ll}\p{N}])(?=\p{Lu})/u', ' ', $this->value);
        $value = (string) preg_replace('/[^\p{L}\p{N}]+/u', $delimiter, $value);

        return trim($value, $delimiter);
    }

    public function charAt(int $index): Char
    {
        return new Char(
            $this
                ->subString($index, 1)
                ->toString(),
        );
    }

    public function charCodeAt(int $index): int
    {
        return $this->charAt($index)->code();
    }
}
