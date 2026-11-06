# Function reference

Every wrapper takes the subject first, returns a new value instead of changing its arguments,
and throws (or returns `null`) instead of returning `false`/`NAN`. This file is the complete list;
the tables show which PHP function each wrapper replaces and which PHP functions are not wrapped.

## `Arr` (76 methods)

| Method | Wraps |
| --- | --- |
| `Arr::all(array $array, callable $callback)` | array_all (8.4) |
| `Arr::any(array $array, callable $callback)` | array_any (8.4) |
| `Arr::arsort(array $array, int $flags = SORT_REGULAR)` | arsort (returns a sorted copy) |
| `Arr::asort(array $array, int $flags = SORT_REGULAR)` | asort (returns a sorted copy) |
| `Arr::changeKeyCase(array $array, int $case = CASE_LOWER)` | array_change_key_case |
| `Arr::chunk(array $array, int $length, bool $preserveKeys = false)` | array_chunk |
| `Arr::column(array $array, int\|string\|null $column, int\|string\|null $indexKey = null)` | array_column |
| `Arr::combine(array $keys, array $values)` | array_combine |
| `Arr::contains(array $array, mixed $needle, bool $strict = false)` | in_array |
| `Arr::count(array\|\Countable $value, int $mode = COUNT_NORMAL)` | count, sizeof |
| `Arr::countValues(array $array)` | array_count_values |
| `Arr::diff(array $array, array ...$arrays)` | array_diff |
| `Arr::diffAssoc(array $array, array ...$arrays)` | array_diff_assoc |
| `Arr::diffKey(array $array, array ...$arrays)` | array_diff_key |
| `Arr::diffUassoc(array $array, array $other, callable $keyCompare)` | array_diff_uassoc |
| `Arr::diffUkey(array $array, array $other, callable $keyCompare)` | array_diff_ukey |
| `Arr::fill(mixed $value, int $count, int $startIndex = 0)` | array_fill |
| `Arr::fillKeys(array $keys, mixed $value)` | array_fill_keys |
| `Arr::filter(array $array, ?callable $callback = null, int $mode = 0)` | array_filter |
| `Arr::find(array $array, callable $callback)` | array_find (8.4) |
| `Arr::findKey(array $array, callable $callback)` | array_find_key (8.4) |
| `Arr::first(array $array)` | array_first (8.5) |
| `Arr::flip(array $array)` | array_flip |
| `Arr::fromIterable(iterable $iterable, bool $preserveKeys = true)` | iterator_to_array |
| `Arr::implode(array $array, string $separator = '')` | implode, join |
| `Arr::intersect(array $array, array ...$arrays)` | array_intersect |
| `Arr::intersectAssoc(array $array, array ...$arrays)` | array_intersect_assoc |
| `Arr::intersectKey(array $array, array ...$arrays)` | array_intersect_key |
| `Arr::intersectUassoc(array $array, array $other, callable $keyCompare)` | array_intersect_uassoc |
| `Arr::intersectUkey(array $array, array $other, callable $keyCompare)` | array_intersect_ukey |
| `Arr::isList(array $array)` | array_is_list |
| `Arr::keyExists(array $array, int\|string $key)` | array_key_exists, key_exists |
| `Arr::keyFirst(array $array)` | array_key_first |
| `Arr::keyLast(array $array)` | array_key_last |
| `Arr::keys(array $array)` | array_keys |
| `Arr::keysOf(array $array, mixed $value, bool $strict = false)` | array_keys (search form) |
| `Arr::krsort(array $array, int $flags = SORT_REGULAR)` | krsort (returns a sorted copy) |
| `Arr::ksort(array $array, int $flags = SORT_REGULAR)` | ksort (returns a sorted copy) |
| `Arr::last(array $array)` | array_last (8.5) |
| `Arr::map(array $array, ?callable $callback, array ...$arrays)` | array_map |
| `Arr::merge(array $array, array ...$arrays)` | array_merge |
| `Arr::mergeRecursive(array $array, array ...$arrays)` | array_merge_recursive |
| `Arr::natcasesort(array $array)` | natcasesort (returns a sorted copy) |
| `Arr::natsort(array $array)` | natsort (returns a sorted copy) |
| `Arr::pad(array $array, int $length, mixed $value)` | array_pad |
| `Arr::pop(array $array)` | array_pop (returns the array without its last element; use last() to read it) |
| `Arr::product(array $array)` | array_product |
| `Arr::push(array $array, mixed ...$values)` | array_push (returns the new array) |
| `Arr::rand(array $array, int $number = 1)` | array_rand |
| `Arr::range(int\|float\|string $start, int\|float\|string $end, int\|float $step = 1)` | range |
| `Arr::reduce(array $array, callable $callback, mixed $initial = null)` | array_reduce |
| `Arr::replace(array $array, array ...$replacements)` | array_replace |
| `Arr::replaceRecursive(array $array, array ...$replacements)` | array_replace_recursive |
| `Arr::reverse(array $array, bool $preserveKeys = false)` | array_reverse |
| `Arr::rsort(array $array, int $flags = SORT_REGULAR)` | rsort (returns a sorted copy) |
| `Arr::search(array $array, mixed $needle, bool $strict = false)` | array_search (null instead of false) |
| `Arr::shift(array $array)` | array_shift (returns the array without its first element; use first() to read it) |
| `Arr::shuffle(array $array)` | shuffle (returns a shuffled copy) |
| `Arr::slice(array $array, int $offset, ?int $length = null, bool $preserveKeys = false)` | array_slice |
| `Arr::sort(array $array, int $flags = SORT_REGULAR)` | sort (returns a sorted copy) |
| `Arr::splice(array $array, int $offset, ?int $length = null, mixed $replacement = [])` | array_splice (returns the resulting array; use slice() to read the removed part) |
| `Arr::sum(array $array)` | array_sum |
| `Arr::uasort(array $array, callable $callback)` | uasort (returns a sorted copy) |
| `Arr::udiff(array $array, array $other, callable $valueCompare)` | array_udiff |
| `Arr::udiffAssoc(array $array, array $other, callable $valueCompare)` | array_udiff_assoc |
| `Arr::udiffUassoc(array $array, array $other, callable $valueCompare, callable $keyCompare)` | array_udiff_uassoc |
| `Arr::uintersect(array $array, array $other, callable $valueCompare)` | array_uintersect |
| `Arr::uintersectAssoc(array $array, array $other, callable $valueCompare)` | array_uintersect_assoc |
| `Arr::uintersectUassoc(array $array, array $other, callable $valueCompare, callable $keyCompare)` | array_uintersect_uassoc |
| `Arr::uksort(array $array, callable $callback)` | uksort (returns a sorted copy) |
| `Arr::unique(array $array, int $flags = SORT_STRING)` | array_unique |
| `Arr::unshift(array $array, mixed ...$values)` | array_unshift (returns the new array) |
| `Arr::usort(array $array, callable $callback)` | usort (returns a sorted copy) |
| `Arr::values(array $array)` | array_values |
| `Arr::walk(array $array, callable $callback, mixed $arg = null)` | array_walk (returns the modified copy) |
| `Arr::walkRecursive(array $array, callable $callback, mixed $arg = null)` | array_walk_recursive (returns the modified copy) |

### Not wrapped in `Arr`

| PHP function | Why |
| --- | --- |
| compact, extract | Operate on the local variable scope. |
| current, key, next, prev, reset, end, each, pos | Internal array pointer: stateful, not usable on immutable arrays (`each` was removed in PHP 8). Use `first()`, `last()`, `keyFirst()`, `keyLast()`, or `foreach`. |
| array_multisort | Sorts several arrays at once through by-reference arguments. Use `usort()`/`uasort()` with a comparison callback. |
| sizeof, key_exists, join, pos | Aliases: use `count()`, `keyExists()`, `implode()`. |
| iterator_count, iterator_apply | Iterator functions rather than array functions. Use `count(Arr::fromIterable($it))`. |

## `Str` (88 methods)

| Method | Wraps |
| --- | --- |
| `Str::addCSlashes(string $string, string $characters)` | addcslashes |
| `Str::addSlashes(string $string)` | addslashes |
| `Str::binToHex(string $string)` | bin2hex |
| `Str::byteLength(string $string)` | strlen |
| `Str::chr(int $codePoint)` | mb_chr (chr; throws instead of false) |
| `Str::chunkSplit(string $string, int $length = 76, string $separator = "\r\n")` | chunk_split (UTF-8 aware) |
| `Str::compare(string $string1, string $string2)` | strcmp |
| `Str::compareFirst(string $string1, string $string2, int $length)` | strncmp (UTF-8 aware) |
| `Str::compareFirstIgnoreCase(string $string1, string $string2, int $length)` | strncasecmp (UTF-8 aware) |
| `Str::compareIgnoreCase(string $string1, string $string2)` | strcasecmp (UTF-8 aware) |
| `Str::compareLocale(string $string1, string $string2)` | strcoll |
| `Str::compareNatural(string $string1, string $string2)` | strnatcmp |
| `Str::compareNaturalIgnoreCase(string $string1, string $string2)` | strnatcasecmp (UTF-8 aware) |
| `Str::compareSubstr(string $haystack, string $needle, int $offset, ?int $length = null, bool $ignoreCase = false)` | substr_compare (UTF-8 aware) |
| `Str::contains(string $haystack, string $needle)` | str_contains |
| `Str::convertCase(string $string, int $mode = MB_CASE_TITLE, ?string $encoding = null)` | mb_convert_case |
| `Str::convertEncoding(array\|string $string, string $toEncoding, array\|string\|null $fromEncoding = null)` | mb_convert_encoding |
| `Str::count(string $haystack, string $needle)` | mb_substr_count (substr_count) |
| `Str::countChars(string $string, int $mode = 0)` | count_chars (byte based) |
| `Str::crc32(string $string)` | crc32 |
| `Str::cspan(string $string, string $characters, int $offset = 0, ?int $length = null)` | strcspn (byte based) |
| `Str::decrement(string $string)` | str_decrement |
| `Str::detectEncoding(string $string, array\|string\|null $encodings = null, bool $strict = false)` | mb_detect_encoding (null instead of false) |
| `Str::endsWith(string $haystack, string $needle)` | str_ends_with |
| `Str::explode(string $string, string $separator, int $limit = PHP_INT_MAX)` | explode |
| `Str::format(string $format, mixed ...$values)` | sprintf |
| `Str::formatArray(string $format, array $values)` | vsprintf |
| `Str::hexToBin(string $hex)` | hex2bin (throws instead of warning + false) |
| `Str::htmlEntities(string $string, int $flags = ENT_QUOTES \| ENT_SUBSTITUTE \| ENT_HTML401, ?string $encoding = null, bool $doubleEncode = true)` | htmlentities |
| `Str::htmlEntityDecode(string $string, int $flags = ENT_QUOTES \| ENT_SUBSTITUTE \| ENT_HTML401, ?string $encoding = null)` | html_entity_decode |
| `Str::htmlSpecialChars(string $string, int $flags = ENT_QUOTES \| ENT_SUBSTITUTE \| ENT_HTML401, ?string $encoding = null, bool $doubleEncode = true)` | htmlspecialchars |
| `Str::htmlSpecialCharsDecode(string $string, int $flags = ENT_QUOTES \| ENT_SUBSTITUTE \| ENT_HTML401)` | htmlspecialchars_decode |
| `Str::increment(string $string)` | str_increment |
| `Str::ipos(string $haystack, string $needle, int $offset = 0)` | mb_stripos (stripos; null instead of false) |
| `Str::ireplace(string\|array $subject, string\|array $search, string\|array $replace)` | str_ireplace |
| `Str::ireplaceCount(string\|array $subject, string\|array $search, string\|array $replace)` | str_ireplace (returns [result, count]) |
| `Str::istr(string $haystack, string $needle, bool $beforeNeedle = false)` | mb_stristr (stristr; null instead of false) |
| `Str::isValidEncoding(string $string, ?string $encoding = null)` | mb_check_encoding |
| `Str::lastIpos(string $haystack, string $needle, int $offset = 0)` | mb_strripos (strripos; null instead of false) |
| `Str::lastIstr(string $haystack, string $needle, bool $beforeNeedle = false)` | mb_strrichr (null instead of false) |
| `Str::lastPos(string $haystack, string $needle, int $offset = 0)` | mb_strrpos (strrpos; null instead of false) |
| `Str::lastStr(string $haystack, string $needle, bool $beforeNeedle = false)` | mb_strrchr (strrchr; null instead of false) |
| `Str::lcfirst(string $string)` | lcfirst (UTF-8 aware) |
| `Str::length(string $string)` | mb_strlen (strlen) |
| `Str::levenshtein(string $string1, string $string2, int $insertionCost = 1, int $replacementCost = 1, int $deletionCost = 1)` | levenshtein (UTF-8 aware) |
| `Str::lower(string $string)` | mb_strtolower (strtolower) |
| `Str::ltrim(string $string, ?string $characters = null)` | ltrim (UTF-8 aware; ".." ranges are only supported for ASCII characters) |
| `Str::md5(string $string, bool $binary = false)` | md5 |
| `Str::metaphone(string $string, int $maxPhonemes = 0)` | metaphone (English, byte based) |
| `Str::nl2br(string $string, bool $useXhtml = true)` | nl2br |
| `Str::ord(string $character)` | mb_ord (ord) |
| `Str::pad(string $string, int $length, string $padString = ' ', int $type = STR_PAD_RIGHT)` | mb_str_pad (str_pad) |
| `Str::parseCsv(string $string, string $separator = ',', string $enclosure = '"', string $escape = '')` | str_getcsv |
| `Str::parseStr(string $string)` | parse_str (returns the array) |
| `Str::pbrk(string $string, string $characters)` | strpbrk (null instead of false) |
| `Str::pos(string $haystack, string $needle, int $offset = 0)` | mb_strpos (strpos; null instead of false) |
| `Str::quoteMeta(string $string)` | quotemeta |
| `Str::randomBytes(int $length)` | random_bytes |
| `Str::repeat(string $string, int $times)` | str_repeat |
| `Str::replace(string\|array $subject, string\|array $search, string\|array $replace)` | str_replace |
| `Str::replaceCount(string\|array $subject, string\|array $search, string\|array $replace)` | str_replace (returns [result, count]) |
| `Str::replaceSubstr(string $string, string $replacement, int $offset, ?int $length = null)` | substr_replace (UTF-8 aware, single string form) |
| `Str::reverse(string $string)` | strrev (UTF-8 aware) |
| `Str::rot13(string $string)` | str_rot13 |
| `Str::rtrim(string $string, ?string $characters = null)` | rtrim (UTF-8 aware; ".." ranges are only supported for ASCII characters) |
| `Str::scan(string $string, string $format)` | sscanf |
| `Str::sha1(string $string, bool $binary = false)` | sha1 |
| `Str::shuffle(string $string)` | str_shuffle (UTF-8 aware) |
| `Str::similarity(string $string1, string $string2)` | similar_text (the percent output, returned as a float) |
| `Str::similarText(string $string1, string $string2)` | similar_text (UTF-8 aware) |
| `Str::soundex(string $string)` | soundex (English, byte based) |
| `Str::span(string $string, string $mask, int $offset = 0, ?int $length = null)` | strspn (byte based) |
| `Str::split(string $string, int $length = 1)` | mb_str_split (str_split) |
| `Str::startsWith(string $haystack, string $needle)` | str_starts_with |
| `Str::str(string $haystack, string $needle, bool $beforeNeedle = false)` | mb_strstr (strstr, strchr; null instead of false) |
| `Str::stripCSlashes(string $string)` | stripcslashes |
| `Str::stripSlashes(string $string)` | stripslashes |
| `Str::stripTags(string $string, array\|string\|null $allowedTags = null)` | strip_tags |
| `Str::substr(string $string, int $offset, ?int $length = null)` | mb_substr (substr) |
| `Str::translate(string $string, string\|array $from, ?string $to = null)` | strtr |
| `Str::trim(string $string, ?string $characters = null)` | trim (UTF-8 aware; ".." ranges are only supported for ASCII characters) |
| `Str::trimWidth(string $string, int $start, int $width, string $trimMarker = '')` | mb_strimwidth |
| `Str::ucfirst(string $string)` | ucfirst (UTF-8 aware) |
| `Str::ucwords(string $string, string $separators = " \t\r\n\f\v")` | ucwords (UTF-8 aware) |
| `Str::upper(string $string)` | mb_strtoupper (strtoupper) |
| `Str::width(string $string)` | mb_strwidth |
| `Str::wordCount(string $string, int $format = 0, ?string $characters = null)` | str_word_count (UTF-8 aware; positions are character offsets) |
| `Str::wordwrap(string $string, int $width = 75, string $break = "\n", bool $cut = false)` | wordwrap (UTF-8 aware) |

### Not wrapped in `Str`

| PHP function | Why |
| --- | --- |
| strtok | Keeps hidden state between calls. Use `explode()`/`split()`. |
| printf, vprintf, fprintf, vfprintf, print_r | Write to output or streams instead of returning a string. Use `format()`/`formatArray()`. |
| chop, strchr, join | Aliases: use `rtrim()`, `str()`, `Arr::implode()`. |
| implode | Takes an array, so it lives in `Arr::implode()`. |
| setlocale, localeconv, nl_langinfo, strptime | Process-wide locale state. |
| crypt, password_* | Security primitives that should not be wrapped casually: use PHP directly. |
| convert_uuencode, convert_uudecode, quoted_printable_encode, quoted_printable_decode, hebrev, utf8_encode, utf8_decode, get_html_translation_table | Legacy encodings, deprecated or niche. |
| base64_*, urlencode, urldecode, rawurlencode, rawurldecode, http_build_query, parse_url | URL and transport encodings, not string manipulation. |
| md5_file, sha1_file | File functions. |
| uniqid, lcg_value | Not reproducible or deprecated; use `Math::randomInt()`/`Str::randomBytes()`. |
| mb_ereg*, mb_split, mb_regex_* | Regex functions: out of scope here. |
| mb_internal_encoding, mb_language, mb_http_*, mb_detect_order, mb_substitute_character, mb_get_info, mb_list_encodings, mb_encoding_aliases, mb_scrub, mb_strcut, mb_convert_kana, mb_convert_variables, mb_encode_*/mb_decode_*, mb_send_mail, mb_output_handler, mb_preferred_mime_name | Global configuration or niche multibyte features. |
| mb_parse_str | Use `parseStr()`. |
| strval, intval, floatval, boolval | Type conversion, not string functions. |

## `Math` (48 methods)

| Method | Wraps |
| --- | --- |
| `Math::abs(int\|float $number)` | abs |
| `Math::acos(int\|float $number)` | acos (throws instead of NAN) |
| `Math::acosh(int\|float $number)` | acosh (throws instead of NAN) |
| `Math::asin(int\|float $number)` | asin (throws instead of NAN) |
| `Math::asinh(int\|float $number)` | asinh |
| `Math::atan(int\|float $number)` | atan |
| `Math::atan2(int\|float $y, int\|float $x)` | atan2 |
| `Math::atanh(int\|float $number)` | atanh (throws instead of NAN/INF) |
| `Math::baseConvert(string $number, int $fromBase, int $toBase)` | base_convert (throws on invalid digits; loses precision above PHP_INT_MAX) |
| `Math::binToDec(string $binary)` | bindec (throws on invalid digits) |
| `Math::ceil(int\|float $number)` | ceil |
| `Math::cos(int\|float $angle)` | cos |
| `Math::cosh(int\|float $angle)` | cosh |
| `Math::decToBin(int $number)` | decbin |
| `Math::decToHex(int $number)` | dechex |
| `Math::decToOct(int $number)` | decoct |
| `Math::degToRad(int\|float $degrees)` | deg2rad |
| `Math::exp(int\|float $number)` | exp |
| `Math::expm1(int\|float $number)` | expm1 |
| `Math::fdiv(int\|float $dividend, int\|float $divisor)` | fdiv (IEEE division, never throws) |
| `Math::floor(int\|float $number)` | floor |
| `Math::fmod(int\|float $dividend, int\|float $divisor)` | fmod (throws instead of NAN) |
| `Math::format(int\|float $number, int $decimals = 0, ?string $decimalSeparator = '.', ?string $thousandsSeparator = ',')` | number_format |
| `Math::hexToDec(string $hex)` | hexdec (throws on invalid digits) |
| `Math::hypot(int\|float $x, int\|float $y)` | hypot |
| `Math::intdiv(int $dividend, int $divisor)` | intdiv |
| `Math::isFinite(float $number)` | is_finite |
| `Math::isInfinite(float $number)` | is_infinite |
| `Math::isNan(float $number)` | is_nan |
| `Math::log(int\|float $number, int\|float $base = M_E)` | log (throws instead of NAN/-INF) |
| `Math::log10(int\|float $number)` | log10 (throws instead of NAN/-INF) |
| `Math::log1p(int\|float $number)` | log1p (throws instead of NAN/-INF) |
| `Math::max(int\|float ...$values)` | max |
| `Math::min(int\|float ...$values)` | min |
| `Math::mtMax()` | mt_getrandmax |
| `Math::mtRand(int $min, int $max)` | mt_rand |
| `Math::mtSeed(int $seed)` | mt_srand |
| `Math::octToDec(string $octal)` | octdec (throws on invalid digits) |
| `Math::pi()` | pi |
| `Math::pow(int\|float $base, int\|float $exponent)` | pow (throws instead of INF/NAN for undefined results) |
| `Math::radToDeg(int\|float $radians)` | rad2deg |
| `Math::randomInt(int $min, int $max)` | random_int (cryptographically secure) |
| `Math::round(int\|float $number, int $precision = 0, int $mode = PHP_ROUND_HALF_UP)` | round |
| `Math::sin(int\|float $angle)` | sin |
| `Math::sinh(int\|float $angle)` | sinh |
| `Math::sqrt(int\|float $number)` | sqrt (throws instead of NAN) |
| `Math::tan(int\|float $angle)` | tan |
| `Math::tanh(int\|float $angle)` | tanh |

### Not wrapped in `Math`

| PHP function | Why |
| --- | --- |
| array_sum, array_product | Array functions: `Arr::sum()`, `Arr::product()`. |
| rand, srand, getrandmax | Legacy aliases of the `mt_*` functions: use `mtRand()`, `mtSeed()`, `mtMax()`. |
| lcg_value | Deprecated in PHP 8.4. |
| random_bytes | Returns a string: `Str::randomBytes()`. |
| bc*, gmp_* | Arbitrary-precision extensions, not part of the core math functions. |
