<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

class Arr implements \ArrayAccess, \Countable
{
    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';
    const SORT_NAT = 'NAT';

    use Arrayable;

    private $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public static function createFrom(array $items): Arr
    {
        return new static($items);
    }

    public static function fill($value, int $size, int $startIndex = 0): Arr
    {
        return new static(
            array_fill($startIndex, $size, $value)
        );
    }

    public function add($item): Arr
    {
        array_push($this->items, $item);
        return $this;
    }

    public function merge(array $items): Arr
    {
        return new static(
            array_merge($this->items, $items)
        );
    }

    public function slice(int $offset, int $length): Arr
    {
        return new static(
            array_slice($this->items, $offset, $length)
        );
    }

    public function map(callable $callable): Arr
    {
        $this->items = array_map($callable, $this->items);
        return $this;
    }

    public function each(callable $callback): Arr
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    public function where(callable $where): Arr
    {
        $items = array_filter($this->items, $where);
        return new static($items);
    }

    public function column($key): Arr
    {
        return new static(
            array_column($this->items, $key)
        );
    }

    public function combine(array $array): Arr
    {
        return new static(
            array_combine($this->items, $array)
        );
    }

    public function diff(array $array): Arr
    {
        return new static(
            array_diff($this->items, $array)
        );
    }

    public function flip(): Arr
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
    public function sort(string $type = Arr::SORT_ASC): Arr
    {
        $items = $this->items;
        switch ($type) {
            case Arr::SORT_DESC:
                arsort($items);
                break;
            case Arr::SORT_NAT:
                natsort($items);
                break;
            default;
                asort($items);
        }
        $this->items = $items;
        return $this;
    }

    public function whereSort(callable $where): Arr
    {
        $items = $this->items;
        uasort($items, $where);
        $this->items = $items;
        return $this;
    }

    public function keys(): Arr
    {
        return new static(array_keys($this->items));
    }

    public function chunk(int $size = 1): Arr
    {
        if ($size < 0) {
            $size = 1;
        }

        return new static(
            array_chunk($this->items, $size)
        );
    }

    public function pad(int $padSize, $value): Arr
    {
        return new static(
            array_pad($this->items, $padSize, $value)
        );
    }

    public function keyExists($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Gets the first element after applying the filter
     *
     * @param callable $where
     * @return mixed
     */
    public function first(callable $where)
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
    public function last(callable $where)
    {
        $items = $this->where($where)->all();
        return array_pop($items);
    }

    /**
     * @param string|null $key
     * @return int|float
     */
    public function sum(string $key = null)
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
     * @return array|mixed
     */
    public function rand(int $size = 1)
    {
        return array_rand($this->all(), $size);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->all());
    }
}
