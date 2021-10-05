<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

trait Arrayable
{
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
}
