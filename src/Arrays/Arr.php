<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Immutable array wrapper: every method returns a new instance.
 *
 * @implements \IteratorAggregate<array-key, mixed>
 * @implements \ArrayAccess<array-key, mixed>
 */
class Arr implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    use Arrayable;

    public function __construct(private readonly array $items = []) {}

    public static function createFrom(array $items): static
    {
        return new static($items);
    }

    public static function fill(mixed $value, int $size, int $startIndex = 0): static
    {
        return new static(
            array_fill($startIndex, $size, $value),
        );
    }

    /**
     * @throws InvalidArgumentException when the JSON is invalid or not an array/object
     */
    public static function fromJson(string $json): static
    {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new InvalidArgumentException('Invalid JSON: ' . $e->getMessage(), 0, $e);
        }

        if (!\is_array($data)) {
            throw new InvalidArgumentException('JSON must decode to an array or an object.');
        }

        return new static($data);
    }

    public function add(mixed $item): static
    {
        return new static([...$this->items, $item]);
    }

    public function merge(array $items): static
    {
        return new static(
            array_merge($this->items, $items),
        );
    }

    public function slice(int $offset, ?int $length = null): static
    {
        return new static(
            \array_slice($this->items, $offset, $length),
        );
    }

    public function map(callable $callable): static
    {
        return new static(
            array_map($callable, $this->items),
        );
    }

    /**
     * Calls $callback($item, $key) for each element; returning false stops the loop.
     */
    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Keeps the elements for which the callback returns true. Without a
     * callback, falsy elements are removed. Keys are preserved.
     */
    public function filter(?callable $callback = null): static
    {
        return new static(
            $callback === null
                ? array_filter($this->items)
                : array_filter($this->items, $callback),
        );
    }

    /**
     * Alias of filter() with a required callback.
     */
    public function where(callable $where): static
    {
        return $this->filter($where);
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function column(string|int|null $key): static
    {
        return new static(
            array_column($this->items, $key),
        );
    }

    /**
     * Extracts a (dot notation) key from each item, optionally indexed by another key.
     */
    public function pluck(string|int $key, string|int|null $indexBy = null): static
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

        return new static($result);
    }

    /**
     * Groups items by a (dot notation) key or by the result of a callback.
     * Each group is itself an Arr.
     */
    public function groupBy(string|int|callable $key): static
    {
        $groups = [];
        foreach ($this->items as $k => $item) {
            $groupKey = $key instanceof \Closure || (\is_callable($key) && !\is_string($key))
                ? $key($item, $k)
                : (\is_array($item) ? self::dotGet($item, (string) $key, '') : '');
            $groups[$groupKey][] = $item;
        }

        return new static(array_map(fn(array $group) => new static($group), $groups));
    }

    /**
     * @throws InvalidArgumentException when the lengths of the two arrays differ
     */
    public function combine(array $array): static
    {
        if (\count($this->items) !== \count($array)) {
            throw new InvalidArgumentException('Keys and values must have the same number of elements.');
        }

        return new static(
            array_combine($this->items, $array),
        );
    }

    public function diff(array $array): static
    {
        return new static(
            array_diff($this->items, $array),
        );
    }

    public function flip(): static
    {
        return new static(
            array_flip($this->items),
        );
    }

    public function unique(): static
    {
        return new static(
            array_unique($this->items, SORT_REGULAR),
        );
    }

    public function reverse(bool $preserveKeys = false): static
    {
        return new static(
            array_reverse($this->items, $preserveKeys),
        );
    }

    /**
     * Collapses nested arrays into a single level (up to $depth levels).
     */
    public function flatten(int $depth = PHP_INT_MAX): static
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

        return new static($result);
    }

    public function sort(SortOrder $order = SortOrder::Asc): static
    {
        $items = $this->items;
        match ($order) {
            SortOrder::Desc => arsort($items),
            SortOrder::Natural => natsort($items),
            SortOrder::Asc => asort($items),
        };

        return new static($items);
    }

    public function whereSort(callable $where): static
    {
        $items = $this->items;
        uasort($items, $where);

        return new static($items);
    }

    public function keys(): static
    {
        return new static(array_keys($this->items));
    }

    public function values(): static
    {
        return new static(array_values($this->items));
    }

    /**
     * @throws InvalidArgumentException when $size is lower than 1
     */
    public function chunk(int $size = 1): static
    {
        if ($size < 1) {
            throw new InvalidArgumentException('Chunk size must be at least 1.');
        }

        return new static(
            array_chunk($this->items, $size),
        );
    }

    public function pad(int $padSize, mixed $value): static
    {
        return new static(
            array_pad($this->items, $padSize, $value),
        );
    }

    public function keyExists(string|int $key): bool
    {
        return \array_key_exists($key, $this->items);
    }

    /**
     * Whether a (dot notation) key exists.
     */
    public function has(string|int $key): bool
    {
        $missing = new \stdClass();

        return self::dotGet($this->items, (string) $key, $missing) !== $missing;
    }

    /**
     * Gets a value using dot notation, e.g. get('user.address.city').
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return self::dotGet($this->items, (string) $key, $default);
    }

    /**
     * Returns a new instance with a value set using dot notation.
     */
    public function set(string|int $key, mixed $value): static
    {
        $items = $this->items;
        $target = &$items;
        foreach (explode('.', (string) $key) as $segment) {
            if (!isset($target[$segment]) || !\is_array($target[$segment])) {
                $target[$segment] = [];
            }
            $target = &$target[$segment];
        }
        $target = $value;

        return new static($items);
    }

    /**
     * Returns a new instance without the given top-level keys.
     */
    public function except(string|int ...$keys): static
    {
        return new static(array_diff_key($this->items, array_flip($keys)));
    }

    /**
     * Whether the array contains a value (or an item matching a Closure).
     */
    public function contains(mixed $value, bool $strict = false): bool
    {
        if ($value instanceof \Closure) {
            foreach ($this->items as $key => $item) {
                if ($value($item, $key)) {
                    return true;
                }
            }

            return false;
        }

        return \in_array($value, $this->items, $strict);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Gets the first element (matching the filter, when given).
     */
    public function first(?callable $where = null, mixed $default = null): mixed
    {
        foreach ($this->items as $key => $item) {
            if ($where === null || $where($item, $key)) {
                return $item;
            }
        }

        return $default;
    }

    /**
     * Gets the last element (matching the filter, when given).
     */
    public function last(?callable $where = null, mixed $default = null): mixed
    {
        foreach (array_reverse($this->items, true) as $key => $item) {
            if ($where === null || $where($item, $key)) {
                return $item;
            }
        }

        return $default;
    }

    public function sum(?string $key = null): int|float
    {
        $items = $key === null ? $this->items : $this->column($key)->all();

        return array_sum($items);
    }

    /**
     * Returns one random key, or an array of random keys when $size > 1.
     *
     * @throws InvalidArgumentException when $size is out of range
     */
    public function rand(int $size = 1): int|string|array
    {
        if ($size < 1 || $size > \count($this->items)) {
            throw new InvalidArgumentException('Size must be between 1 and the number of elements.');
        }

        return array_rand($this->items, $size);
    }

    /**
     * Lazily processes the items through generators.
     */
    public function lazy(): LazyArr
    {
        return LazyArr::from($this->items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function toJson(int $flags = 0): string
    {
        return json_encode($this->items, $flags | JSON_THROW_ON_ERROR);
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

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
