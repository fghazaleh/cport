<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Lazy, immutable pipeline built on generators. Nothing runs until the result is
 * consumed (iterated, all(), count(), first(), ...), so it works on large or infinite sources.
 *
 * Callbacks receive the value only.
 *
 * @implements \IteratorAggregate<array-key, mixed>
 */
final readonly class LazyCollection implements \IteratorAggregate
{
    /**
     * @param \Closure(): iterable<array-key, mixed> $source factory returning the items to iterate
     */
    private function __construct(private \Closure $source) {}

    /**
     * Creates a lazy collection from an array, any iterable, or a Closure that returns or yields items.
     * A Closure can be iterated more than once; a bare generator can only be consumed once.
     *
     * @param iterable<array-key, mixed>|\Closure(): iterable<array-key, mixed> $source
     */
    public static function of(iterable|\Closure $source): self
    {
        if ($source instanceof \Closure) {
            return new self(static fn(): iterable => $source());
        }

        return new self(static fn(): iterable => $source);
    }

    /**
     * Lazily produces the integers from $start to $end (inclusive).
     *
     * @throws InvalidArgumentException when $step is lower than 1
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
                yield $key => $callback($item);
            }
        });
    }

    /**
     * Keeps the items for which the callback returns true. Without a callback, falsy items are removed.
     */
    public function filter(?callable $callback = null): self
    {
        $source = $this->source;

        return new self(static function () use ($source, $callback): \Generator {
            foreach ($source() as $key => $item) {
                if ($callback === null ? (bool) $item : $callback($item)) {
                    yield $key => $item;
                }
            }
        });
    }

    /**
     * Removes the items for which the callback returns true.
     */
    public function reject(callable $callback): self
    {
        return $this->filter(static fn(mixed $item): bool => !$callback($item));
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
     * Lazily groups items into plain arrays of $size items.
     *
     * @throws InvalidArgumentException when $size is lower than 1
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
     * Discards the keys: items are numbered from 0 as they are produced.
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

    // ---- consuming (these run the pipeline) -----------------------------------------

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $carry = $initial;

        foreach (($this->source)() as $item) {
            $carry = $callback($carry, $item);
        }

        return $carry;
    }

    /**
     * The first item (matching the callback, when given), or $default. Stops as soon as it is found.
     */
    public function first(?callable $callback = null, mixed $default = null): mixed
    {
        foreach (($this->source)() as $item) {
            if ($callback === null || $callback($item)) {
                return $item;
            }
        }

        return $default;
    }

    public function isEmpty(): bool
    {
        foreach (($this->source)() as $ignored) {
            return false;
        }

        return true;
    }

    public function count(): int
    {
        $count = 0;

        foreach (($this->source)() as $ignored) {
            $count++;
        }

        return $count;
    }

    /**
     * Runs the pipeline into a plain array (later items overwrite earlier ones with the same key;
     * call values() first to keep every item).
     *
     * @return array<array-key, mixed>
     */
    public function all(): array
    {
        return Arr::fromIterable($this->getIterator());
    }

    public function getIterator(): \Iterator
    {
        $items = ($this->source)();

        return $items instanceof \Iterator ? $items : (static fn(): \Generator => yield from $items)();
    }
}
