# cport

cport wraps PHP's array, string and math functions in three static classes with **one consistent API**:
`Arr`, `Str` and `Math`. Nobody remembers whether it is `array_map($fn, $array)` or
`array_filter($array, $fn)`, whether `strpos` returns `false` or `0`, or what `sqrt(-1)` gives you.
With cport you type `Arr::`, `Str::` or `Math::`, let your IDE complete the name, and the arguments always
come in the same order.

[![CI](https://github.com/fghazaleh/cport/actions/workflows/ci.yml/badge.svg)](https://github.com/fghazaleh/cport/actions/workflows/ci.yml)

```bash
composer require fghazaleh/cport
```

Requires PHP 8.5+ and `ext-mbstring`.

## Rules every method follows

| Rule | Example |
| --- | --- |
| **Subject first**, always | `Arr::map($array, $fn)`, `Arr::filter($array, $fn)`, `Str::replace($subject, $search, $replace)`, `Str::contains($haystack, $needle)` |
| **No `array_` / `str` prefixes** | `Arr::chunk()`, `Arr::unique()`, `Str::pad()`, `Str::repeat()` |
| **Nothing is changed in place.** By-reference functions return the new value | `$sorted = Arr::sort($array)`, `$rest = Arr::pop($array)`, `Arr::push($array, 4)` |
| **`null` instead of `false`** when nothing was found | `Arr::search()`, `Str::pos()`, `Str::str()` |
| **Throws instead of `NAN`, `INF` or a warning** | `Math::sqrt(-1)`, `Math::log(0)`, `Str::hexToBin('zz')` |
| **UTF-8 by default** for strings | `Str::length('héllo') === 5`, `Str::upper('é') === 'É'` |
| **Pure functions**, no state, no objects | all classes are `final` with a private constructor |

```php
use FG\Support\Arr;
use FG\Support\Math;
use FG\Support\Str;

Arr::map([1, 2, 3], fn ($n) => $n * 2);          // [2, 4, 6]
Arr::filter([1, 2, 3, 4], fn ($n) => $n > 2);    // [2 => 3, 3 => 4]
Arr::reduce([1, 2, 3], fn ($c, $n) => $c + $n, 0); // 6
Arr::sort([3, 1, 2]);                            // [1, 2, 3] (the argument is untouched)
Arr::search(['a', 'b'], 'z');                    // null, not false
Arr::first([]);                                  // null
Arr::find([1, 2, 3], fn ($n) => $n > 1);         // 2

Str::length('héllo');                            // 5
Str::pos('héllo wörld', 'w');                    // 6
Str::substr('héllo', 1, 3);                      // 'éll'
Str::pad('é', 5, '-', STR_PAD_BOTH);             // '--é--'
Str::wordwrap('The quick brown fox', 10, "\n", true);
Str::levenshtein('kitten', 'sitting');           // 3
Str::compare('a', 'b');                          // -1 (always -1, 0 or 1)

Math::round(2.5);                                // 3.0
Math::max(3, 9, 4);                              // 9
Math::sqrt(-1);                                  // throws InvalidArgumentException
Math::baseConvert('ff', 16, 2);                  // '11111111'
Math::decToHex(255);                             // 'ff'
```

## What is wrapped

The complete list, with the PHP function behind every method and the functions that are deliberately **not**
wrapped (and why), is in [docs/FUNCTIONS.md](docs/FUNCTIONS.md).

| Class | Methods | Covers |
| --- | --- | --- |
| `Arr` | 76 | `array_*`, the sort family, `range`, `count`, `in_array`, `implode`, `iterator_to_array`, plus `find`, `findKey`, `any`, `all`, `first`, `last` (PHP 8.4/8.5 functions that work on every version) |
| `Str` | 88 | `str*`, `substr*`, `mb_*`, `trim` family, `html*`, `*slashes`, `sprintf` family, `levenshtein`, `similar_text`, `wordwrap`, hashes |
| `Math` | 48 | `abs`, `round`, `log*`, `pow`, `sqrt`, trigonometry, base conversion, `number_format`, `mt_rand`, `random_int` |

Naming follows the PHP function without its prefix, in camelCase, for example `array_key_first` →
`Arr::keyFirst()`, `str_word_count` → `Str::wordCount()`, `bindec` → `Math::binToDec()`. A few readability renames:
`in_array` → `Arr::contains()`, `strstr` → `Str::str()`, `substr_replace` → `Str::replaceSubstr()`,
`rad2deg` → `Math::radToDeg()`.

### Differences from PHP worth knowing

- **Comparisons** (`Str::compare*`) always return `-1`, `0` or `1`.
- **User-callback set functions** (`udiff`, `uintersect`, `diffUassoc`, ...) take exactly one other array.
- **`Arr::pop`, `shift`, `splice`** return the remaining array. Read the removed element first with `Arr::last()`,
  `Arr::first()` or `Arr::slice()`.
- **`Str::trim` family** supports `..` ranges only for ASCII characters.
- **`Str::soundex`, `metaphone`, `rot13`, `md5`, `sha1`, `crc32`, `countChars`, `span`, `cspan`** are byte based by nature.
- **`Math::baseConvert`** and the other base functions lose precision above `PHP_INT_MAX`, like PHP.

## Fluent classes: `Collection`, `Text`, `Number`

Chainable, **immutable** value objects built on the static classes. They only add what PHP has no function for
(`snake`, `slug`, `pluck`, `groupBy`, statistics, ...) plus a small set of chain basics; for everything else use
`Arr`, `Str` and `Math` directly.

```php
use FG\Support\Collection;
use FG\Support\Number;
use FG\Support\Text;

Collection::of($employees)
    ->filter(fn ($e) => $e['active'])
    ->sortBy('salary', descending: true)
    ->pluck('name')
    ->all();

Collection::of($orders)->groupBy('customer.id');       // ['c1' => [...], 'c2' => [...]]
Collection::of($orders)->average('total');             // 42.5, or null when empty

Text::of('Héllo Wörld')->slug()->toString();           // 'héllo-wörld'
Text::of('fooBar baz')->snake()->toString();           // 'foo_bar_baz'
Text::of('  some text ')->trim()->limit(4)->upper();   // Text('SOME...')

Number::of('19.99')->multiply(3)->round(1)->format(2); // '60.00'
Number::of(23)->toOrdinal();                           // '23rd'
Number::of(1)->percentOf(4)->value();                  // 25.0
```

Rules:

- **A fluent class returns its own type or a plain PHP value.** `Text::explode()` returns an `array`,
  `Collection::average()` returns `?float`, `Collection::implode()` returns a `string`. No fluent class depends on another
  one (a test enforces it); to keep chaining across classes, wrap the value: `Collection::of($text->explode(','))`.
- **They connect through PHP interfaces:** `Collection::of()` accepts any `iterable`, `Text::of()` and `Number::of()` accept any
  `Stringable`.
- **Callbacks receive the value only.** Methods that take a key accept a dot-notation key (`'user.address.city'`) or a `Closure`.
- **Full method names**, no aliases: `plus`, `minus`, `multiply`, `divide`, `modulo`, `power`, `squareRoot`.

| Class | Methods |
| --- | --- |
| `Collection` | `of`, `fromJson`, `all`, `count`, `isEmpty`, `isNotEmpty`, `first`, `last`, `get`, `has`, `contains`, `keys`, `values`, `map`, `filter`, `reject`, `reduce`, `merge`, `unique`, `sort`, `sortBy`, `pluck`, `groupBy`, `flatten`, `implode`, `sum`, `average`, `min`, `max`, `median`, `toJson` |
| `Text` | `of`, `toString`, `length`, `isEmpty`, `isNotEmpty`, `equals`, `upper`, `lower`, `ucfirst`, `lcfirst`, `title`, `camel`, `studly`, `snake`, `kebab`, `slug`, `trim`, `ltrim`, `rtrim`, `pad`, `append`, `prepend`, `repeat`, `reverse`, `replace`, `remove`, `substr`, `limit`, `before`, `after`, `beforeLast`, `between`, `contains`, `startsWith`, `endsWith`, `wordCount`, `explode`, `words` |
| `Number` | `of`, `value`, `toInt`, `toFloat`, `plus`, `minus`, `multiply`, `divide`, `modulo`, `power`, `squareRoot`, `abs`, `round`, `ceil`, `floor`, `clamp`, `compare`, `equals`, `isBetween`, `isEven`, `isOdd`, `isWhole`, `percentOf`, `format`, `toOrdinal` |

More fluent classes and methods will be added in later releases.

## Development

```bash
composer install
composer test      # PHPUnit
composer cs        # check code style (PHP-CS-Fixer, dry run)
composer cs:fix    # fix code style
composer check     # both
```

Each wrapper has at least one test that compares it with the native PHP function (where behaviour is identical)
or with an explicit expected value (where it intentionally differs).
