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

    public static function createFrom(array $items): self
    {
        return new static($items);
    }

    public function add($item): self
    {
        array_push($this->items, $item);
        return $this;
    }

    public function merge(array $items): self
    {
        return new static(
            array_merge($this->items, $items)
        );
    }

    public function slice(int $offset, int $length): self
    {
        return new static(
            array_slice($this->items, $offset, $length)
        );
    }

    public function map(callable $callable): self
    {
        $this->items = array_map($callable, $this->items);
        return $this;
    }

    public function each(callable $callback): self
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    public function where(callable $where): self
    {
        $items = array_filter($this->items, $where);
        return new static($items);
    }

    public function column($key): self
    {
        return new static(
            array_column($this->items, $key)
        );
    }

    public function combine(array $array): self
    {
        return new static(
            array_combine($this->items, $array)
        );
    }

    public function diff(array $array): self
    {
        return new static(
            array_diff($this->items, $array)
        );
    }

    public function flip(): self
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
    public function sort(string $type = Arr::SORT_ASC): self
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

    public function whereSort(callable $where): self
    {
        $items = $this->items;
        uasort($items, $where);
        $this->items = $items;
        return $this;
    }

    public function keys(): self
    {
        return new static(array_keys($this->items));
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

    public function all(): array
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->all());
    }
}