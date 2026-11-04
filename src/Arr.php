<?php

declare(strict_types=1);

namespace FG\Support;

/**
 * Static, typed wrappers around PHP's array functions.
 *
 * Every method takes the subject first, never changes its arguments and
 * never returns false on failure. See docs/FUNCTIONS.md for the full list.
 */
final class Arr
{
    private function __construct() {}

    /**
     * Wraps array_all (8.4).
     */
    public static function all(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Wraps array_any (8.4).
     */
    public static function any(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Wraps arsort (returns a sorted copy).
     */
    public static function arsort(array $array, int $flags = SORT_REGULAR): array
    {
        arsort($array, $flags);

        return $array;
    }

    /**
     * Wraps asort (returns a sorted copy).
     */
    public static function asort(array $array, int $flags = SORT_REGULAR): array
    {
        asort($array, $flags);

        return $array;
    }

    /**
     * Wraps array_change_key_case.
     */
    public static function changeKeyCase(array $array, int $case = CASE_LOWER): array
    {
        return array_change_key_case($array, $case);
    }

    /**
     * Wraps array_chunk.
     */
    public static function chunk(array $array, int $length, bool $preserveKeys = false): array
    {
        return array_chunk($array, $length, $preserveKeys);
    }

    /**
     * Wraps array_column.
     */
    public static function column(array $array, int|string|null $column, int|string|null $indexKey = null): array
    {
        return array_column($array, $column, $indexKey);
    }

    /**
     * Wraps array_combine.
     */
    public static function combine(array $keys, array $values): array
    {
        return array_combine($keys, $values);
    }

    /**
     * Wraps in_array.
     */
    public static function contains(array $array, mixed $needle, bool $strict = false): bool
    {
        return \in_array($needle, $array, $strict);
    }

    /**
     * Wraps count, sizeof.
     */
    public static function count(array|\Countable $value, int $mode = COUNT_NORMAL): int
    {
        return \count($value, $mode);
    }

    /**
     * Wraps array_count_values.
     */
    public static function countValues(array $array): array
    {
        return array_count_values($array);
    }

    /**
     * Wraps array_diff.
     */
    public static function diff(array $array, array ...$arrays): array
    {
        return array_diff($array, ...$arrays);
    }

    /**
     * Wraps array_diff_assoc.
     */
    public static function diffAssoc(array $array, array ...$arrays): array
    {
        return array_diff_assoc($array, ...$arrays);
    }

    /**
     * Wraps array_diff_key.
     */
    public static function diffKey(array $array, array ...$arrays): array
    {
        return array_diff_key($array, ...$arrays);
    }

    /**
     * Wraps array_diff_uassoc.
     */
    public static function diffUassoc(array $array, array $other, callable $keyCompare): array
    {
        return array_diff_uassoc($array, $other, $keyCompare);
    }

    /**
     * Wraps array_diff_ukey.
     */
    public static function diffUkey(array $array, array $other, callable $keyCompare): array
    {
        return array_diff_ukey($array, $other, $keyCompare);
    }

    /**
     * Wraps array_fill.
     */
    public static function fill(mixed $value, int $count, int $startIndex = 0): array
    {
        return array_fill($startIndex, $count, $value);
    }

    /**
     * Wraps array_fill_keys.
     */
    public static function fillKeys(array $keys, mixed $value): array
    {
        return array_fill_keys($keys, $value);
    }

    /**
     * Wraps array_filter.
     */
    public static function filter(array $array, ?callable $callback = null, int $mode = 0): array
    {
        return array_filter($array, $callback, $mode);
    }

    /**
     * Wraps array_find (8.4).
     */
    public static function find(array $array, callable $callback): mixed
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Wraps array_find_key (8.4).
     */
    public static function findKey(array $array, callable $callback): int|string|null
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Wraps array_first (8.5).
     */
    public static function first(array $array): mixed
    {
        return $array === [] ? null : $array[array_key_first($array)];
    }

    /**
     * Wraps array_flip.
     */
    public static function flip(array $array): array
    {
        return array_flip($array);
    }

    /**
     * Wraps iterator_to_array.
     */
    public static function fromIterable(iterable $iterable, bool $preserveKeys = true): array
    {
        return iterator_to_array($iterable, $preserveKeys);
    }

    /**
     * Wraps implode, join.
     */
    public static function implode(array $array, string $separator = ''): string
    {
        return implode($separator, $array);
    }

    /**
     * Wraps array_intersect.
     */
    public static function intersect(array $array, array ...$arrays): array
    {
        return array_intersect($array, ...$arrays);
    }

    /**
     * Wraps array_intersect_assoc.
     */
    public static function intersectAssoc(array $array, array ...$arrays): array
    {
        return array_intersect_assoc($array, ...$arrays);
    }

    /**
     * Wraps array_intersect_key.
     */
    public static function intersectKey(array $array, array ...$arrays): array
    {
        return array_intersect_key($array, ...$arrays);
    }

    /**
     * Wraps array_intersect_uassoc.
     */
    public static function intersectUassoc(array $array, array $other, callable $keyCompare): array
    {
        return array_intersect_uassoc($array, $other, $keyCompare);
    }

    /**
     * Wraps array_intersect_ukey.
     */
    public static function intersectUkey(array $array, array $other, callable $keyCompare): array
    {
        return array_intersect_ukey($array, $other, $keyCompare);
    }

    /**
     * Wraps array_is_list.
     */
    public static function isList(array $array): bool
    {
        return array_is_list($array);
    }

    /**
     * Wraps array_key_exists, key_exists.
     */
    public static function keyExists(array $array, int|string $key): bool
    {
        return \array_key_exists($key, $array);
    }

    /**
     * Wraps array_key_first.
     */
    public static function keyFirst(array $array): int|string|null
    {
        return array_key_first($array);
    }

    /**
     * Wraps array_key_last.
     */
    public static function keyLast(array $array): int|string|null
    {
        return array_key_last($array);
    }

    /**
     * Wraps array_keys.
     */
    public static function keys(array $array): array
    {
        return array_keys($array);
    }

    /**
     * Wraps array_keys (search form).
     */
    public static function keysOf(array $array, mixed $value, bool $strict = false): array
    {
        return array_keys($array, $value, $strict);
    }

    /**
     * Wraps krsort (returns a sorted copy).
     */
    public static function krsort(array $array, int $flags = SORT_REGULAR): array
    {
        krsort($array, $flags);

        return $array;
    }

    /**
     * Wraps ksort (returns a sorted copy).
     */
    public static function ksort(array $array, int $flags = SORT_REGULAR): array
    {
        ksort($array, $flags);

        return $array;
    }

    /**
     * Wraps array_last (8.5).
     */
    public static function last(array $array): mixed
    {
        return $array === [] ? null : $array[array_key_last($array)];
    }

    /**
     * Wraps array_map.
     */
    public static function map(array $array, ?callable $callback, array ...$arrays): array
    {
        return array_map($callback, $array, ...$arrays);
    }

    /**
     * Wraps array_merge.
     */
    public static function merge(array $array, array ...$arrays): array
    {
        return array_merge($array, ...$arrays);
    }

    /**
     * Wraps array_merge_recursive.
     */
    public static function mergeRecursive(array $array, array ...$arrays): array
    {
        return array_merge_recursive($array, ...$arrays);
    }

    /**
     * Wraps natcasesort (returns a sorted copy).
     */
    public static function natcasesort(array $array): array
    {
        natcasesort($array);

        return $array;
    }

    /**
     * Wraps natsort (returns a sorted copy).
     */
    public static function natsort(array $array): array
    {
        natsort($array);

        return $array;
    }

    /**
     * Wraps array_pad.
     */
    public static function pad(array $array, int $length, mixed $value): array
    {
        return array_pad($array, $length, $value);
    }

    /**
     * Wraps array_pop (returns the array without its last element; use last() to read it).
     */
    public static function pop(array $array): array
    {
        array_pop($array);

        return $array;
    }

    /**
     * Wraps array_product.
     */
    public static function product(array $array): int|float
    {
        return array_product($array);
    }

    /**
     * Wraps array_push (returns the new array).
     */
    public static function push(array $array, mixed ...$values): array
    {
        array_push($array, ...$values);

        return $array;
    }

    /**
     * Wraps array_rand.
     */
    public static function rand(array $array, int $number = 1): int|string|array
    {
        return array_rand($array, $number);
    }

    /**
     * Wraps range.
     */
    public static function range(int|float|string $start, int|float|string $end, int|float $step = 1): array
    {
        return range($start, $end, $step);
    }

    /**
     * Wraps array_reduce.
     */
    public static function reduce(array $array, callable $callback, mixed $initial = null): mixed
    {
        return array_reduce($array, $callback, $initial);
    }

    /**
     * Wraps array_replace.
     */
    public static function replace(array $array, array ...$replacements): array
    {
        return array_replace($array, ...$replacements);
    }

    /**
     * Wraps array_replace_recursive.
     */
    public static function replaceRecursive(array $array, array ...$replacements): array
    {
        return array_replace_recursive($array, ...$replacements);
    }

    /**
     * Wraps array_reverse.
     */
    public static function reverse(array $array, bool $preserveKeys = false): array
    {
        return array_reverse($array, $preserveKeys);
    }

    /**
     * Wraps rsort (returns a sorted copy).
     */
    public static function rsort(array $array, int $flags = SORT_REGULAR): array
    {
        rsort($array, $flags);

        return $array;
    }

    /**
     * Wraps array_search (null instead of false).
     */
    public static function search(array $array, mixed $needle, bool $strict = false): int|string|null
    {
        $result = array_search($needle, $array, $strict);

        return $result === false ? null : $result;
    }

    /**
     * Wraps array_shift (returns the array without its first element; use first() to read it).
     */
    public static function shift(array $array): array
    {
        array_shift($array);

        return $array;
    }

    /**
     * Wraps shuffle (returns a shuffled copy).
     */
    public static function shuffle(array $array): array
    {
        shuffle($array);

        return $array;
    }

    /**
     * Wraps array_slice.
     */
    public static function slice(array $array, int $offset, ?int $length = null, bool $preserveKeys = false): array
    {
        return \array_slice($array, $offset, $length, $preserveKeys);
    }

    /**
     * Wraps sort (returns a sorted copy).
     */
    public static function sort(array $array, int $flags = SORT_REGULAR): array
    {
        sort($array, $flags);

        return $array;
    }

    /**
     * Wraps array_splice (returns the resulting array; use slice() to read the removed part).
     */
    public static function splice(array $array, int $offset, ?int $length = null, mixed $replacement = []): array
    {
        array_splice($array, $offset, $length, $replacement);

        return $array;
    }

    /**
     * Wraps array_sum.
     */
    public static function sum(array $array): int|float
    {
        return array_sum($array);
    }

    /**
     * Wraps uasort (returns a sorted copy).
     */
    public static function uasort(array $array, callable $callback): array
    {
        uasort($array, $callback);

        return $array;
    }

    /**
     * Wraps array_udiff.
     */
    public static function udiff(array $array, array $other, callable $valueCompare): array
    {
        return array_udiff($array, $other, $valueCompare);
    }

    /**
     * Wraps array_udiff_assoc.
     */
    public static function udiffAssoc(array $array, array $other, callable $valueCompare): array
    {
        return array_udiff_assoc($array, $other, $valueCompare);
    }

    /**
     * Wraps array_udiff_uassoc.
     */
    public static function udiffUassoc(array $array, array $other, callable $valueCompare, callable $keyCompare): array
    {
        return array_udiff_uassoc($array, $other, $valueCompare, $keyCompare);
    }

    /**
     * Wraps array_uintersect.
     */
    public static function uintersect(array $array, array $other, callable $valueCompare): array
    {
        return array_uintersect($array, $other, $valueCompare);
    }

    /**
     * Wraps array_uintersect_assoc.
     */
    public static function uintersectAssoc(array $array, array $other, callable $valueCompare): array
    {
        return array_uintersect_assoc($array, $other, $valueCompare);
    }

    /**
     * Wraps array_uintersect_uassoc.
     */
    public static function uintersectUassoc(array $array, array $other, callable $valueCompare, callable $keyCompare): array
    {
        return array_uintersect_uassoc($array, $other, $valueCompare, $keyCompare);
    }

    /**
     * Wraps uksort (returns a sorted copy).
     */
    public static function uksort(array $array, callable $callback): array
    {
        uksort($array, $callback);

        return $array;
    }

    /**
     * Wraps array_unique.
     */
    public static function unique(array $array, int $flags = SORT_STRING): array
    {
        return array_unique($array, $flags);
    }

    /**
     * Wraps array_unshift (returns the new array).
     */
    public static function unshift(array $array, mixed ...$values): array
    {
        array_unshift($array, ...$values);

        return $array;
    }

    /**
     * Wraps usort (returns a sorted copy).
     */
    public static function usort(array $array, callable $callback): array
    {
        usort($array, $callback);

        return $array;
    }

    /**
     * Wraps array_values.
     */
    public static function values(array $array): array
    {
        return array_values($array);
    }

    /**
     * Wraps array_walk (returns the modified copy).
     */
    public static function walk(array $array, callable $callback, mixed $arg = null): array
    {
        if (\func_num_args() > 2) {
            array_walk($array, $callback, $arg);
        } else {
            array_walk($array, $callback);
        }

        return $array;
    }

    /**
     * Wraps array_walk_recursive (returns the modified copy).
     */
    public static function walkRecursive(array $array, callable $callback, mixed $arg = null): array
    {
        if (\func_num_args() > 2) {
            array_walk_recursive($array, $callback, $arg);
        } else {
            array_walk_recursive($array, $callback);
        }

        return $array;
    }
}
