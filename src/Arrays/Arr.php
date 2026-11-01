<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

class Arr implements \ArrayAccess, \Countable
{
    public const string SORT_ASC = 'ASC';
    public const string SORT_DESC = 'DESC';
    public const string SORT_NAT = 'NAT';

    use Arrayable;

    public function __construct(private array $items = [])
    {
    }

    public static function createFrom(array $items): static
    {
        return new static($items);
    }

    public static function fill(mixed $value, int $size, int $startIndex = 0): static
    {
        return new static(
            array_fill($startIndex, $size, $value)
        );
    }

    public function add(mixed $item): static
    {
        array_push($this->items, $item);
        return $this;
    }

    public function merge(array $items): static
    {
        return new static(
            array_merge($this->items, $items)
        );
    }

    public function slice(int $offset, int $length): static
    {
        return new static(
            array_slice($this->items, $offset, $length)
        );
    }

    public function map(callable $callable): static
    {
        $this->items = array_map($callable, $this->items);
        return $this;
    }

    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    public function where(callable $where): static
    {
        $items = array_filter($this->items, $where);
        return new static($items);
    }

    public function column(string|int|null $key): static
    {
        return new static(
            array_column($this->items, $key)
        );
    }

    public function combine(array $array): static
    {
        return new static(
            array_combine($this->items, $array)
        );
    }

    public function diff(array $array): static
    {
        return new static(
            array_diff($this->items, $array)
        );
    }

    public function flip(): static
    {
        return new static(
            array_flip($this->items)
        );
    }

    /**
     * ASC : Sorting by ascending.
     * DESC : Sorting by descending.
     * NAT : Sorting by natural algorithm.
     *
     *
     * @param string $type 'ASC' or 'DESC', 'NAT'
     * @return Arr
     */
    public function sort(string $type = self::SORT_ASC): static
    {
        $items = $this->items;
        match ($type) {
            self::SORT_DESC => arsort($items),
            self::SORT_NAT => natsort($items),
            default => asort($items),
        };
        $this->items = $items;
        return $this;
    }

    public function whereSort(callable $where): static
    {
        $items = $this->items;
        uasort($items, $where);
        $this->items = $items;
        return $this;
    }

    public function keys(): static
    {
        return new static(array_keys($this->items));
    }

    public function chunk(int $size = 1): static
    {
        if ($size < 0) {
            $size = 1;
        }

        return new static(
            array_chunk($this->items, $size)
        );
    }

    public function pad(int $padSize, mixed $value): static
    {
        return new static(
            array_pad($this->items, $padSize, $value)
        );
    }

    public function keyExists(string|int $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Gets the first element after applying the filter
     *
     * @param callable $where
     * @return mixed
     */
    public function first(callable $where): mixed
    {
        $items = $this->where($where)->all();
        return array_shift($items);
    }

    /**
     * Gets the last element after applying the filter
     *
     * @param callable $where
     * @return mixed
     */
    public function last(callable $where): mixed
    {
        $items = $this->where($where)->all();
        return array_pop($items);
    }

    /**
     * @param string|null $key
     */
    public function sum(?string $key = null): int|float
    {
        $items = $this->all();
        if ($key !== null) {
            $items = $this->column($key)->all();
        }
        return array_sum($items);
    }

    /**
     * Returns random array keys
     *
     * @param int $size
     *
     */
    public function rand(int $size = 1): int|string|array
    {
        return array_rand($this->all(), $size);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->all());
    }
}
