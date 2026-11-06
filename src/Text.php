<?php

declare(strict_types=1);

namespace FG\Support;

/**
 * Immutable, UTF-8 aware string: every method returns a new Text or a plain value.
 */
final readonly class Text implements \JsonSerializable, \Stringable
{
    private function __construct(private string $value) {}

    public static function of(string|\Stringable $value = ''): self
    {
        return new self((string) $value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function length(): int
    {
        return Str::length($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function isNotEmpty(): bool
    {
        return $this->value !== '';
    }

    public function equals(string|\Stringable $other, bool $ignoreCase = false): bool
    {
        $other = (string) $other;

        return $ignoreCase ? Str::compareIgnoreCase($this->value, $other) === 0 : $this->value === $other;
    }

    // ---- case -------------------------------------------------------------

    public function upper(): self
    {
        return new self(Str::upper($this->value));
    }

    public function lower(): self
    {
        return new self(Str::lower($this->value));
    }

    public function ucfirst(): self
    {
        return new self(Str::ucfirst($this->value));
    }

    public function lcfirst(): self
    {
        return new self(Str::lcfirst($this->value));
    }

    /**
     * "hello WORLD" => "Hello World".
     */
    public function title(): self
    {
        return new self(Str::convertCase($this->value, MB_CASE_TITLE));
    }

    /**
     * "foo_bar baz" => "fooBarBaz".
     */
    public function camel(): self
    {
        return new self(Str::lcfirst($this->studly()->value));
    }

    /**
     * "foo_bar baz" => "FooBarBaz".
     */
    public function studly(): self
    {
        $words = array_map(
            static fn(string $word): string => Str::ucfirst(Str::lower($word)),
            $this->splitWords(),
        );

        return new self(implode('', $words));
    }

    /**
     * "fooBar baz" => "foo_bar_baz".
     */
    public function snake(string $separator = '_'): self
    {
        return new self(Str::lower(implode($separator, $this->splitWords())));
    }

    /**
     * "fooBar baz" => "foo-bar-baz".
     */
    public function kebab(): self
    {
        return $this->snake('-');
    }

    /**
     * URL friendly, lowercase text. Unicode letters and digits are kept.
     */
    public function slug(string $separator = '-'): self
    {
        return new self(Str::lower(
            trim((string) preg_replace('/[^\p{L}\p{N}]+/u', $separator, $this->value), $separator),
        ));
    }

    // ---- trimming and padding ----------------------------------------------

    public function trim(?string $characters = null): self
    {
        return new self(Str::trim($this->value, $characters));
    }

    public function ltrim(?string $characters = null): self
    {
        return new self(Str::ltrim($this->value, $characters));
    }

    public function rtrim(?string $characters = null): self
    {
        return new self(Str::rtrim($this->value, $characters));
    }

    public function pad(int $length, string $padString = ' ', int $type = STR_PAD_RIGHT): self
    {
        return new self(Str::pad($this->value, $length, $padString, $type));
    }

    // ---- composing ------------------------------------------------------------

    public function append(string|\Stringable ...$parts): self
    {
        return new self($this->value . implode('', array_map('strval', $parts)));
    }

    public function prepend(string|\Stringable ...$parts): self
    {
        return new self(implode('', array_map('strval', $parts)) . $this->value);
    }

    public function repeat(int $times): self
    {
        return new self(Str::repeat($this->value, $times));
    }

    public function reverse(): self
    {
        return new self(Str::reverse($this->value));
    }

    /**
     * @param array<string>|string $search
     * @param array<string>|string $replace
     */
    public function replace(array|string $search, array|string $replace): self
    {
        return new self((string) Str::replace($this->value, $search, $replace));
    }

    /**
     * @param array<string>|string $search
     */
    public function remove(array|string $search): self
    {
        return $this->replace($search, '');
    }

    public function substr(int $offset, ?int $length = null): self
    {
        return new self(Str::substr($this->value, $offset, $length));
    }

    /**
     * Cuts the text to $length characters and adds $end when something was cut.
     */
    public function limit(int $length, string $end = '...'): self
    {
        if (Str::length($this->value) <= $length) {
            return $this;
        }

        return new self(Str::substr($this->value, 0, $length) . $end);
    }

    /**
     * The part of the text before the first $search (the whole text when it is not found).
     */
    public function before(string $search): self
    {
        $position = $search === '' ? null : Str::pos($this->value, $search);

        return $position === null ? $this : new self(Str::substr($this->value, 0, $position));
    }

    /**
     * The part of the text after the first $search (the whole text when it is not found).
     */
    public function after(string $search): self
    {
        $position = $search === '' ? null : Str::pos($this->value, $search);

        return $position === null ? $this : new self(Str::substr($this->value, $position + Str::length($search)));
    }

    /**
     * The part of the text before the last $search (the whole text when it is not found).
     */
    public function beforeLast(string $search): self
    {
        $position = $search === '' ? null : Str::lastPos($this->value, $search);

        return $position === null ? $this : new self(Str::substr($this->value, 0, $position));
    }

    /**
     * The part of the text between the first $from and the last $to.
     */
    public function between(string $from, string $to): self
    {
        if ($from === '' || $to === '') {
            return $this;
        }

        return $this->after($from)->beforeLast($to);
    }

    // ---- inspecting ----------------------------------------------------------

    public function contains(string $needle): bool
    {
        return Str::contains($this->value, $needle);
    }

    public function startsWith(string $prefix): bool
    {
        return Str::startsWith($this->value, $prefix);
    }

    public function endsWith(string $suffix): bool
    {
        return Str::endsWith($this->value, $suffix);
    }

    public function wordCount(): int
    {
        return Str::wordCount($this->value);
    }

    /**
     * Splits into a plain array of strings.
     *
     * @return list<string>
     */
    public function explode(string $separator, int $limit = PHP_INT_MAX): array
    {
        return array_values(Str::explode($this->value, $separator, $limit));
    }

    /**
     * The words of the text as a plain array.
     *
     * @return list<string>
     */
    public function words(): array
    {
        return array_values(Str::wordCount($this->value, 1));
    }

    /**
     * @return list<string>
     */
    private function splitWords(): array
    {
        $value = (string) preg_replace('/(?<=[\p{Ll}\p{N}])(?=\p{Lu})|(?<=\p{Lu})(?=\p{Lu}\p{Ll})/u', ' ', $this->value);
        $value = trim((string) preg_replace('/[^\p{L}\p{N}]+/u', ' ', $value));

        return $value === '' ? [] : explode(' ', $value);
    }
}
