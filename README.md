# cport

cport is a PHP support library wrapping Array and String PHP functions.

[![CI](https://github.com/fghazaleh/cport/actions/workflows/ci.yml/badge.svg)](https://github.com/fghazaleh/cport/actions/workflows/ci.yml)

## Requirements

- PHP 8.5 or newer
- `ext-mbstring`, `ext-json`

```bash
composer require fghazaleh/cport
```

## Strings: `Str`

`Str` is **immutable** and **multibyte (UTF-8) safe**: every method returns a new instance.

```php
use FG\Support\Strings\Str;

$str = new Str('Héllo Wörld');
// or
$str = Str::fromString('Héllo Wörld');

$str->length();              // 11
$str->toUpper();             // "HÉLLO WÖRLD"
$str->slug();                // "héllo-wörld"
$str->snake();               // "héllo_wörld"
```

| Group | Methods |
| --- | --- |
| Trim | `trim`, `rtrim`, `ltrim` |
| Case | `toUpper`, `toLower`, `toUpperFirst`, `toLowerFirst`, `title`, `camel`, `studly`, `snake`, `kebab`, `slug` |
| Inspect | `length`, `isEmpty`, `isNotEmpty`, `wordCount`, `contains`, `any` (case-insensitive), `startsWith`, `endsWith`, `indexOf`, `lastIndexOf`, `charAt`, `charCodeAt` |
| Transform | `replace`, `subString`, `appendAfter`, `appendBefore`, `stripTags`, `reverse`, `pad`, `repeat`, `limit` |
| Slice | `before`, `after`, `between`, `beforeLast` |
| Convert | `explode` (returns `Arr`), `parseQueryString` (returns `Arr`), `toMd5`, `toString` |

A `Str` is iterable and yields `Char` objects (characters, not bytes).

## Arrays: `Arr`

`Arr` is **immutable**: every method returns a new instance and the original is never changed.
It is `Countable`, `IteratorAggregate`, `JsonSerializable` and read-only `ArrayAccess`
(assigning or unsetting an offset throws `ImmutableException`).

```php
use FG\Support\Arrays\Arr;
use FG\Support\Arrays\SortOrder;

$arr = new Arr([3, 1, 2]);
// or
$arr = Arr::createFrom([3, 1, 2]);

$sorted = $arr->sort(SortOrder::Desc);   // [3, 2, 1]; $arr is unchanged

$users = Arr::createFrom([
    ['id' => 1, 'name' => 'Ann', 'team' => 'a'],
    ['id' => 2, 'name' => 'Bob', 'team' => 'b'],
]);

$users->pluck('name', 'id')->all();      // [1 => 'Ann', 2 => 'Bob']
$users->groupBy('team')->keys()->all();  // ['a', 'b']

$config = Arr::createFrom(['db' => ['host' => 'localhost']]);
$config->get('db.host');                 // 'localhost'
$config->set('db.port', 3306)->all();    // new Arr with the extra key
```

| Group | Methods |
| --- | --- |
| Create | `createFrom`, `fill`, `fromJson` |
| Transform | `add`, `merge`, `slice`, `map`, `filter`, `where`, `reduce`, `column`, `pluck`, `groupBy`, `combine`, `diff`, `flip`, `unique`, `reverse`, `flatten`, `pad`, `chunk` |
| Sort | `sort(SortOrder)`, `whereSort` |
| Keys | `keys`, `values`, `keyExists`, `has`, `get`, `set`, `except` |
| Inspect | `contains`, `isEmpty`, `isNotEmpty`, `first`, `last`, `rand`, `count` |
| Statistics | `sum`, `avg`, `min`, `max`, `median`, `mode`, `percentile`, `countBy` |
| Convert | `all`, `toJson`, `lazy` |

Statistics accept an optional column key (`$users->avg('salary')`) and return `null` when the array is empty
(`mode()` returns an empty `Arr`).

`SortOrder` is an enum: `SortOrder::Asc`, `SortOrder::Desc`, `SortOrder::Natural`.

Invalid input throws `FG\Support\Exceptions\InvalidArgumentException`
(for example `combine()` with arrays of different lengths, or `chunk(0)`).

### Lazy arrays: `LazyArr`

`LazyArr` is a generator pipeline: nothing runs until you consume the result, so it works on large or infinite sources.

```php
use FG\Support\Arrays\LazyArr;

$firstEvenSquares = LazyArr::range(1, PHP_INT_MAX)
    ->filter(fn ($n) => $n % 2 === 0)
    ->map(fn ($n) => $n ** 2)
    ->take(3)
    ->values()
    ->all();                              // [4, 16, 36]

$lazy = (new Arr($rows))->lazy();         // from an Arr
$arr  = $lazy->toArr();                   // back to an Arr
```

Methods: `from`, `range`, `map`, `filter`, `take`, `skip`, `chunk`, `values`, `reduce`, `first`, `each`, `all`, `toArr`.

## Math: `Math` and `Num`

`Math` is a set of typed static helpers. It replaces the standalone
[`fghazaleh/math`](https://github.com/fghazaleh/math) package, and domain errors
(empty input, division by zero, negative square root, ...) throw
`InvalidArgumentException` instead of returning `NAN`/`INF`.

```php
use FG\Support\Math\Math;

Math::max(3, 9, 4);              // 9
Math::avg(1, 2, 3, 4);           // 2.5
Math::median(4, 1, 2, 3);        // 2.5
Math::mode(3, 1, 3, 1, 2);       // [1, 3]
Math::percentile([15, 20, 35, 40, 50], 40); // 29.0
Math::clamp(15, 0, 10);          // 10
Math::percentage(1, 4);          // 25.0
Math::log(8, 2);                 // 3.0
```

Methods: `pi`, `abs`, `ceil` / `roundUp`, `floor` / `roundDown`, `round`, `log`, `log10`, `log2`,
`pow`, `sqrt`, `max`, `min`, `sum`, `avg`, `median`, `mode`, `percentile`, `clamp`, `between`, `percentage`.

`Num` is an immutable number value object:

```php
use FG\Support\Math\Num;

Num::of(1234.5)->add(10)->mul(2)->round()->format(2);  // "2,489.00"
Num::of(23)->toOrdinal();                              // "23rd"
Num::of(1)->percentOf(4);                              // 25.0
Num::of('12')->isEven();                               // true
```

Methods: `of`, `value`, `toInt`, `toFloat`, `add`, `sub`, `mul`, `div`, `mod`, `pow`, `sqrt`, `abs`, `round`, `ceil`, `floor`,
`clamp`, `isBetween`, `percentOf`, `compare`, `equals`, `isZero`, `isPositive`, `isNegative`, `isEven`, `isOdd`, `isWhole`,
`format`, `toOrdinal`.

The arithmetic methods also have readable aliases: `plus` (`add`), `minus` (`sub`), `multiply` (`mul`),
`divide` (`div`) and `modulo` (`mod`).

```php
Num::of(10)->plus(5)->multiply(2)->minus(1)->value();  // 29
```

### Migrating from `fghazaleh/math`

Replace `FGhazaleh\Math\Math` with `FG\Support\Math\Math`. `log()`, `max()`, `min()`, `pi()`, `abs()`,
`ceil()`, `floor()`, `roundUp()` and `roundDown()` keep the same names. Parameters are now typed
(`int|float`), `max()` and `min()` throw when called without arguments, and `log()` defaults to the natural logarithm.

## Upgrading to 3.0

- Requires PHP 8.5+.
- `Arr` is now immutable. `add`, `map`, `sort` and `whereSort` used to change the object in place; use the returned value instead. `ArrayAccess` writes throw `ImmutableException`.
- `Arr::SORT_ASC` / `SORT_DESC` / `SORT_NAT` were replaced by the `SortOrder` enum.
- `Arr::first()` / `last()` no longer require a callback and accept a default value.
- `Arr::combine()`, `chunk()` and `rand()` throw `InvalidArgumentException` on invalid input.
- `Str` now uses `mb_*` functions, so `length()`, `subString()`, `indexOf()`, `reverse()`, `pad()` etc. count characters instead of bytes. `Char::code()` returns the Unicode code point.
- `Str::parseQueryString()` always returns an `Arr`.

## Development

```bash
composer install
composer test      # PHPUnit
composer cs        # check code style (PHP-CS-Fixer, dry run)
composer cs:fix    # fix code style
composer check     # both
```
