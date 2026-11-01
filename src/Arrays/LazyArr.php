<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Lazy, immutable pipeline built on generators. Nothing runs until the
 * result is consumed (iterated, all(), toArr(), first(), ...).
 *
 * @implements \IteratorAggregate<mixed, mixed>
 */
final class LazyArr implements \IteratorAggregate
{
    /**
     * @param \Closure(): iterable $source factory returning the items to iterate
     */
    private function __construct(private readonly \Closure $source) {}

    /**
     * Creates a lazy pipeline from an array, a Traversable or a Closure that
     * returns/yields items. A Closure can be iterated more than once; a bare
     * generator can only be consumed once.
     */
    public static function from(iterable|\Closure $source): self
    {
        if ($source instanceof \Closure) {
            return new self(static fn(): iterable => $source());
        }

        return new self(static fn(): iterable => $source);
    }

    /**
     * Lazily produces the integers from $start to $end (inclusive).
     */
    public static function range(int $start, int $end, int $step = 1): self
    {
        if ($step < 1) {
            throw new InvalidArgumentException('Step must be at least 1.');
        }

        return new self(static function () use ($start, $end, $step): \Generator {
            for ($i = $start; $i <= $end; $i += $step) {
                yield $i;
            }
        });
    }

    public function map(callable $callback): self
    {
        $source = $this->source;

        return new self(static function () use ($source, $callback): \Generator {
            foreach ($source() as $key => $item) {
                yield $key => $callback($item, $key);
            }
        });
    }

    public function filter(?callable $callback = null): self
    {
        $source = $this->source;

        return new self(static function () use ($source, $callback): \Generator {
            foreach ($source() as $key => $item) {
                if ($callback === null ? (bool) $item : $callback($item, $key)) {
                    yield $key => $item;
                }
            }
        });
    }

    public function take(int $limit): self
    {
        $source = $this->source;

        return new self(static function () use ($source, $limit): \Generator {
            if ($limit <= 0) {
                return;
            }
            $taken = 0;
            foreach ($source() as $key => $item) {
                yield $key => $item;
                if (++$taken >= $limit) {
                    return;
                }
            }
        });
    }

    public function skip(int $count): self
    {
        $source = $this->source;

        return new self(static function () use ($source, $count): \Generator {
            $skipped = 0;
            foreach ($source() as $key => $item) {
                if ($skipped++ < $count) {
                    continue;
                }
                yield $key => $item;
            }
        });
    }

    /**
     * Lazily groups items into arrays of $size items.
     */
    public function chunk(int $size): self
    {
        if ($size < 1) {
            throw new InvalidArgumentException('Chunk size must be at least 1.');
        }

        $source = $this->source;

        return new self(static function () use ($source, $size): \Generator {
            $chunk = [];
            foreach ($source() as $item) {
                $chunk[] = $item;
                if (\count($chunk) === $size) {
                    yield $chunk;
                    $chunk = [];
                }
            }
            if ($chunk !== []) {
                yield $chunk;
            }
        });
    }

    /**
     * Discards keys; items are re-indexed from 0 as they are produced.
     */
    public function values(): self
    {
        $source = $this->source;

        return new self(static function () use ($source): \Generator {
            foreach ($source() as $item) {
                yield $item;
            }
        });
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $carry = $initial;
        foreach (($this->source)() as $item) {
            $carry = $callback($carry, $item);
        }

        return $carry;
    }

    public function first(?callable $where = null, mixed $default = null): mixed
    {
        foreach (($this->source)() as $key => $item) {
            if ($where === null || $where($item, $key)) {
                return $item;
            }
        }

        return $default;
    }

    public function each(callable $callback): void
    {
        foreach (($this->source)() as $key => $item) {
            if ($callback($item, $key) === false) {
                return;
            }
        }
    }

    public function all(): array
    {
        return iterator_to_array($this->getIterator(), true);
    }

    public function toArr(): Arr
    {
        return new Arr($this->all());
    }

    public function getIterator(): \Iterator
    {
        $items = ($this->source)();

        return $items instanceof \Iterator ? $items : (static fn(): \Generator => yield from $items)();
    }
}
