<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Immutable list/map of values: every method returns a new Collection or a plain value.
 *
 * Callbacks receive the value only. Methods that take a "key" accept a (dot notation)
 * key such as "user.address.city", or a Closure that receives the item.
 *
 * @implements \IteratorAggregate<array-key, mixed>
 */
final readonly class Collection implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /**
     * @param array<array-key, mixed> $items
     */
    private function __construct(private array $items) {}

    /**
     * Creates a collection from an array or any iterable (keys are preserved).
     *
     * @param iterable<array-key, mixed> $items
     */
    public static function of(iterable $items = []): self
    {
        return new self(\is_array($items) ? $items : Arr::fromIterable($items));
    }

    /**
     * @throws InvalidArgumentException when the JSON is invalid or is not an array/object
     */
    public static function fromJson(string $json): self
    {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new InvalidArgumentException('Invalid JSON: ' . $exception->getMessage(), 0, $exception);
        }

        if (!\is_array($data)) {
            throw new InvalidArgumentException('JSON must decode to an array or an object.');
        }

        return new self($data);
    }

    // ---- reading ----------------------------------------------------------------

    /**
     * @return array<array-key, mixed>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function isNotEmpty(): bool
    {
        return $this->items !== [];
    }

    /**
     * The first item (matching the callback, when given), or $default.
     */
    public function first(?callable $callback = null, mixed $default = null): mixed
    {
        foreach ($this->items as $item) {
            if ($callback === null || $callback($item)) {
                return $item;
            }
        }

        return $default;
    }

    /**
     * The last item (matching the callback, when given), or $default.
     */
    public function last(?callable $callback = null, mixed $default = null): mixed
    {
        foreach (array_reverse($this->items, true) as $item) {
            if ($callback === null || $callback($item)) {
                return $item;
            }
        }

        return $default;
    }

    /**
     * Gets a value using dot notation, e.g. get('user.address.city').
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return self::dotGet($this->items, (string) $key, $default);
    }

    public function has(string|int $key): bool
    {
        $missing = new \stdClass();

        return self::dotGet($this->items, (string) $key, $missing) !== $missing;
    }

    public function contains(mixed $value, bool $strict = false): bool
    {
        if ($value instanceof \Closure) {
            foreach ($this->items as $item) {
                if ($value($item)) {
                    return true;
                }
            }

            return false;
        }

        return Arr::contains($this->items, $value, $strict);
    }

    public function implode(string $separator = ''): string
    {
        return Arr::implode($this->items, $separator);
    }

    // ---- transforming -------------------------------------------------------------

    public function keys(): self
    {
        return new self(Arr::keys($this->items));
    }

    public function values(): self
    {
        return new self(Arr::values($this->items));
    }

    public function map(callable $callback): self
    {
        return new self(Arr::map($this->items, $callback));
    }

    /**
     * Keeps the items for which the callback returns true. Without a callback, falsy items are removed.
     */
    public function filter(?callable $callback = null): self
    {
        return new self(Arr::filter($this->items, $callback));
    }

    /**
     * Removes the items for which the callback returns true.
     */
    public function reject(callable $callback): self
    {
        return new self(Arr::filter($this->items, static fn(mixed $item): bool => !$callback($item)));
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        return Arr::reduce($this->items, $callback, $initial);
    }

    /**
     * @param iterable<array-key, mixed> ...$others
     */
    public function merge(iterable ...$others): self
    {
        return new self(Arr::merge($this->items, ...array_map(
            static fn(iterable $other): array => \is_array($other) ? $other : Arr::fromIterable($other),
            $others,
        )));
    }

    public function unique(): self
    {
        return new self(Arr::unique($this->items, SORT_REGULAR));
    }

    /**
     * Sorts the values ascending, keeping the keys.
     */
    public function sort(int $flags = SORT_REGULAR): self
    {
        return new self(Arr::asort($this->items, $flags));
    }

    /**
     * Sorts by a (dot notation) key or by the result of a Closure, keeping the keys.
     */
    public function sortBy(string|int|\Closure $key, bool $descending = false): self
    {
        $keyed = [];
        foreach ($this->items as $itemKey => $item) {
            $keyed[] = [$itemKey, $item, self::valueOf($item, $key)];
        }

        usort(
            $keyed,
            static fn(array $a, array $b): int => $descending ? $b[2] <=> $a[2] : $a[2] <=> $b[2],
        );

        $sorted = [];
        foreach ($keyed as [$itemKey, $item]) {
            $sorted[$itemKey] = $item;
        }

        return new self($sorted);
    }

    /**
     * Extracts a (dot notation) key from each item, optionally indexed by another key.
     */
    public function pluck(string|int $key, string|int|null $indexBy = null): self
    {
        $result = [];

        foreach ($this->items as $item) {
            if (!\is_array($item)) {
                continue;
            }

            $value = self::dotGet($item, (string) $key, null);

            if ($indexBy === null) {
                $result[] = $value;
            } else {
                $result[self::dotGet($item, (string) $indexBy, null)] = $value;
            }
        }

        return new self($result);
    }

    /**
     * Groups items by a (dot notation) key or by the result of a Closure.
     * The result maps each group to a plain array of its items.
     */
    public function groupBy(string|int|\Closure $key): self
    {
        $groups = [];

        foreach ($this->items as $item) {
            $group = self::valueOf($item, $key);
            $groups[\is_int($group) ? $group : (string) $group][] = $item;
        }

        return new self($groups);
    }

    /**
     * Collapses nested arrays into a single level (up to $depth levels).
     */
    public function flatten(int $depth = PHP_INT_MAX): self
    {
        $result = [];
        $walk = static function (array $items, int $depth) use (&$walk, &$result): void {
            foreach ($items as $item) {
                if (\is_array($item) && $depth > 0) {
                    $walk($item, $depth - 1);
                } else {
                    $result[] = $item;
                }
            }
        };
        $walk($this->items, $depth);

        return new self($result);
    }

    // ---- statistics (plain values) ---------------------------------------------------

    /**
     * Sum of the numeric values (or of a column); 0 when empty.
     */
    public function sum(string|int|null $key = null): int|float
    {
        return array_sum($this->numbers($key));
    }

    /**
     * Arithmetic mean, null when empty.
     */
    public function average(string|int|null $key = null): ?float
    {
        $numbers = $this->numbers($key);

        return $numbers === [] ? null : array_sum($numbers) / \count($numbers);
    }

    public function min(string|int|null $key = null): int|float|null
    {
        $numbers = $this->numbers($key);

        return $numbers === [] ? null : min($numbers);
    }

    public function max(string|int|null $key = null): int|float|null
    {
        $numbers = $this->numbers($key);

        return $numbers === [] ? null : max($numbers);
    }

    /**
     * The middle value, or the mean of the two middle values; null when empty.
     */
    public function median(string|int|null $key = null): int|float|null
    {
        $numbers = $this->numbers($key);

        if ($numbers === []) {
            return null;
        }

        sort($numbers);
        $count = \count($numbers);
        $middle = intdiv($count, 2);

        return $count % 2 === 1 ? $numbers[$middle] : ($numbers[$middle - 1] + $numbers[$middle]) / 2;
    }

    // ---- converting ---------------------------------------------------------------------

    public function toJson(int $flags = 0): string
    {
        return json_encode($this->items, $flags | JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    // ---- internals ------------------------------------------------------------------------

    /**
     * @return list<int|float>
     * @throws InvalidArgumentException when a value is not numeric
     */
    private function numbers(string|int|null $key): array
    {
        $values = $key === null
            ? array_values($this->items)
            : array_values($this->pluck($key)->items);

        return array_map(static function (mixed $value): int|float {
            if (\is_int($value) || \is_float($value)) {
                return $value;
            }

            if (\is_string($value) && is_numeric($value)) {
                return $value + 0;
            }

            throw new InvalidArgumentException('Only numeric values can be used in calculations.');
        }, $values);
    }

    private static function valueOf(mixed $item, string|int|\Closure $key): mixed
    {
        if ($key instanceof \Closure) {
            return $key($item);
        }

        return \is_array($item) ? self::dotGet($item, (string) $key, null) : null;
    }

    /**
     * @param array<array-key, mixed> $items
     */
    private static function dotGet(array $items, string $key, mixed $default): mixed
    {
        if (\array_key_exists($key, $items)) {
            return $items[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!\is_array($items) || !\array_key_exists($segment, $items)) {
                return $default;
            }

            $items = $items[$segment];
        }

        return $items;
    }
}
