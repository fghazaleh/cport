# cport

cport is a PHP support library wrapping Array and String PHP functions.

```php
$string = 'This is my first string';

$str = new \FG\Support\Strings\Str($string);
// or
$str = \FG\Support\Strings\Str::fromString($string);
```

Build-in methods in `Str` class.

- trim, rtrim, ltrim
- length
- toUpper, toLower, toUpperFirst, toLowerFirst
- replace
- subString
- appendAfter, appendBefore
- stripTags
- reverse
- toMd5
- startsWith, endsWith
- indexOf
- lastIndexOf
- charAt, charCodeAt
- camel, studly
- contains, any
- pad
- parseQueryString

```php
$array = [1, 2, 3, 4];

$arr = new \FG\Support\Arrays\Arr($array);
// or
$arr = \FG\Support\Arrays\Arr::createFrom($array);
```

Build-in methods in `Arr` class.

- add
- merge
- slice
- map
- each
- where
- column
- combine
- diff
- flip
- sort
- whereSort
- keys, keyExists
- first, last
- all, count
- sum