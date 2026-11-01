<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

use FG\Support\Exceptions\ImmutableException;

/**
 * Read-only ArrayAccess behaviour. Arr is immutable, so writes are rejected.
 */
trait Arrayable
{
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new ImmutableException('Arr is immutable; use set() or add() which return a new instance.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new ImmutableException('Arr is immutable; use except() or filter() which return a new instance.');
    }
}
