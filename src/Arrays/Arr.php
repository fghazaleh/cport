<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

class Arr implements \ArrayAccess, \Countable
{

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

    public function merge(array $items):self
    {
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    public function slice(int $offset, int $length): self
    {
        $this->items = array_slice($this->items, $offset, $length);
        return $this;
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

    public function first(callable $where)
    {
        $items = $this->where($where)->all();
        return array_shift($items);
    }

    public function last(callable $where)
    {
        $items = $this->where($where)->all();
        return array_pop($items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            return;
        }
        unset($this->items[$offset]);
    }

    public function count()
    {
        return count($this->all());
    }
}