<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

/**
 * Static, UTF-8 aware wrappers around PHP's string functions.
 *
 * Every method takes the subject first, never changes its arguments and
 * never returns false on failure. See docs/FUNCTIONS.md for the full list.
 */
final class Str
{
    private function __construct() {}

    /**
     * Wraps addcslashes.
     */
    public static function addCSlashes(string $string, string $characters): string
    {
        return addcslashes($string, $characters);
    }

    /**
     * Wraps addslashes.
     */
    public static function addSlashes(string $string): string
    {
        return addslashes($string);
    }

    /**
     * Wraps bin2hex.
     */
    public static function binToHex(string $string): string
    {
        return bin2hex($string);
    }

    /**
     * Wraps strlen.
     */
    public static function byteLength(string $string): int
    {
        return \strlen($string);
    }

    /**
     * Wraps mb_chr (chr; throws instead of false).
     */
    public static function chr(int $codePoint): string
    {
        $character = mb_chr($codePoint);

        if ($character === false) {
            throw new InvalidArgumentException(\sprintf('%d is not a valid Unicode code point.', $codePoint));
        }

        return $character;
    }

    /**
     * Wraps chunk_split (UTF-8 aware).
     */
    public static function chunkSplit(string $string, int $length = 76, string $separator = "\r\n"): string
    {
        return implode($separator, mb_str_split($string, $length)) . $separator;
    }

    /**
     * Wraps strcmp.
     */
    public static function compare(string $string1, string $string2): int
    {
        return strcmp($string1, $string2) <=> 0;
    }

    /**
     * Wraps strncmp (UTF-8 aware).
     */
    public static function compareFirst(string $string1, string $string2, int $length): int
    {
        return strcmp(mb_substr($string1, 0, $length), mb_substr($string2, 0, $length)) <=> 0;
    }

    /**
     * Wraps strncasecmp (UTF-8 aware).
     */
    public static function compareFirstIgnoreCase(string $string1, string $string2, int $length): int
    {
        return self::compareFirst(mb_strtolower($string1), mb_strtolower($string2), $length);
    }

    /**
     * Wraps strcasecmp (UTF-8 aware).
     */
    public static function compareIgnoreCase(string $string1, string $string2): int
    {
        return strcmp(mb_strtolower($string1), mb_strtolower($string2)) <=> 0;
    }

    /**
     * Wraps strcoll.
     */
    public static function compareLocale(string $string1, string $string2): int
    {
        return strcoll($string1, $string2) <=> 0;
    }

    /**
     * Wraps strnatcmp.
     */
    public static function compareNatural(string $string1, string $string2): int
    {
        return strnatcmp($string1, $string2) <=> 0;
    }

    /**
     * Wraps strnatcasecmp (UTF-8 aware).
     */
    public static function compareNaturalIgnoreCase(string $string1, string $string2): int
    {
        return strnatcmp(mb_strtolower($string1), mb_strtolower($string2)) <=> 0;
    }

    /**
     * Wraps substr_compare (UTF-8 aware).
     */
    public static function compareSubstr(string $haystack, string $needle, int $offset, ?int $length = null, bool $ignoreCase = false): int
    {
        if ($offset > mb_strlen($haystack)) {
            throw new \ValueError('Str::compareSubstr(): Argument #3 ($offset) must be contained in argument #1 ($haystack)');
        }

        $left = mb_substr($haystack, $offset, $length);
        $right = $length === null ? $needle : mb_substr($needle, 0, $length);

        if ($ignoreCase) {
            $left = mb_strtolower($left);
            $right = mb_strtolower($right);
        }

        return strcmp($left, $right) <=> 0;
    }

    /**
     * Wraps str_contains.
     */
    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    /**
     * Wraps mb_convert_case.
     */
    public static function convertCase(string $string, int $mode = MB_CASE_TITLE, ?string $encoding = null): string
    {
        return mb_convert_case($string, $mode, $encoding);
    }

    /**
     * Wraps mb_convert_encoding.
     */
    public static function convertEncoding(array|string $string, string $toEncoding, array|string|null $fromEncoding = null): array|string|false
    {
        return mb_convert_encoding($string, $toEncoding, $fromEncoding);
    }

    /**
     * Wraps mb_substr_count (substr_count).
     */
    public static function count(string $haystack, string $needle): int
    {
        return mb_substr_count($haystack, $needle);
    }

    /**
     * Wraps count_chars (byte based).
     */
    public static function countChars(string $string, int $mode = 0): array|string
    {
        return count_chars($string, $mode);
    }

    /**
     * Wraps crc32.
     */
    public static function crc32(string $string): int
    {
        return crc32($string);
    }

    /**
     * Wraps strcspn (byte based).
     */
    public static function cspan(string $string, string $characters, int $offset = 0, ?int $length = null): int
    {
        return strcspn($string, $characters, $offset, $length);
    }

    /**
     * Wraps str_decrement.
     */
    public static function decrement(string $string): string
    {
        return str_decrement($string);
    }

    /**
     * Wraps mb_detect_encoding (null instead of false).
     */
    public static function detectEncoding(string $string, array|string|null $encodings = null, bool $strict = false): ?string
    {
        $result = mb_detect_encoding($string, $encodings, $strict);

        return $result === false ? null : $result;
    }

    /**
     * Wraps str_ends_with.
     */
    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    /**
     * Wraps explode.
     */
    public static function explode(string $string, string $separator, int $limit = PHP_INT_MAX): array
    {
        return explode($separator, $string, $limit);
    }

    /**
     * Wraps sprintf.
     */
    public static function format(string $format, mixed ...$values): string
    {
        return \sprintf($format, ...$values);
    }

    /**
     * Wraps vsprintf.
     */
    public static function formatArray(string $format, array $values): string
    {
        return vsprintf($format, $values);
    }

    /**
     * Wraps hex2bin (throws instead of warning + false).
     */
    public static function hexToBin(string $hex): string
    {
        if (\strlen($hex) % 2 !== 0 || preg_match('/^[0-9a-fA-F]*$/', $hex) !== 1) {
            throw new InvalidArgumentException('The input is not a valid, even-length hexadecimal string.');
        }

        return (string) hex2bin($hex);
    }

    /**
     * Wraps htmlentities.
     */
    public static function htmlEntities(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ?string $encoding = null, bool $doubleEncode = true): string
    {
        return htmlentities($string, $flags, $encoding, $doubleEncode);
    }

    /**
     * Wraps html_entity_decode.
     */
    public static function htmlEntityDecode(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ?string $encoding = null): string
    {
        return html_entity_decode($string, $flags, $encoding);
    }

    /**
     * Wraps htmlspecialchars.
     */
    public static function htmlSpecialChars(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ?string $encoding = null, bool $doubleEncode = true): string
    {
        return htmlspecialchars($string, $flags, $encoding, $doubleEncode);
    }

    /**
     * Wraps htmlspecialchars_decode.
     */
    public static function htmlSpecialCharsDecode(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401): string
    {
        return htmlspecialchars_decode($string, $flags);
    }

    /**
     * Wraps str_increment.
     */
    public static function increment(string $string): string
    {
        return str_increment($string);
    }

    /**
     * Wraps mb_stripos (stripos; null instead of false).
     */
    public static function ipos(string $haystack, string $needle, int $offset = 0): ?int
    {
        $position = mb_stripos($haystack, $needle, $offset);

        return $position === false ? null : $position;
    }

    /**
     * Wraps str_ireplace.
     */
    public static function ireplace(string|array $subject, string|array $search, string|array $replace): string|array
    {
        return str_ireplace($search, $replace, $subject);
    }

    /**
     * Wraps str_ireplace (returns [result, count]).
     */
    public static function ireplaceCount(string|array $subject, string|array $search, string|array $replace): array
    {
        $result = str_ireplace($search, $replace, $subject, $count);

        return [$result, $count];
    }

    /**
     * Wraps mb_stristr (stristr; null instead of false).
     */
    public static function istr(string $haystack, string $needle, bool $beforeNeedle = false): ?string
    {
        $result = mb_stristr($haystack, $needle, $beforeNeedle);

        return $result === false ? null : $result;
    }

    /**
     * Wraps mb_check_encoding.
     */
    public static function isValidEncoding(string $string, ?string $encoding = null): bool
    {
        return mb_check_encoding($string, $encoding);
    }

    /**
     * Wraps mb_strripos (strripos; null instead of false).
     */
    public static function lastIpos(string $haystack, string $needle, int $offset = 0): ?int
    {
        $position = mb_strripos($haystack, $needle, $offset);

        return $position === false ? null : $position;
    }

    /**
     * Wraps mb_strrichr (null instead of false).
     */
    public static function lastIstr(string $haystack, string $needle, bool $beforeNeedle = false): ?string
    {
        $result = mb_strrichr($haystack, $needle, $beforeNeedle);

        return $result === false ? null : $result;
    }

    /**
     * Wraps mb_strrpos (strrpos; null instead of false).
     */
    public static function lastPos(string $haystack, string $needle, int $offset = 0): ?int
    {
        $position = mb_strrpos($haystack, $needle, $offset);

        return $position === false ? null : $position;
    }

    /**
     * Wraps mb_strrchr (strrchr; null instead of false).
     */
    public static function lastStr(string $haystack, string $needle, bool $beforeNeedle = false): ?string
    {
        $result = mb_strrchr($haystack, $needle, $beforeNeedle);

        return $result === false ? null : $result;
    }

    /**
     * Wraps lcfirst (UTF-8 aware).
     */
    public static function lcfirst(string $string): string
    {
        return $string === '' ? '' : mb_strtolower(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    /**
     * Wraps mb_strlen (strlen).
     */
    public static function length(string $string): int
    {
        return mb_strlen($string);
    }

    /**
     * Wraps levenshtein (UTF-8 aware).
     */
    public static function levenshtein(string $string1, string $string2, int $insertionCost = 1, int $replacementCost = 1, int $deletionCost = 1): int
    {
        $first = mb_str_split($string1);
        $second = mb_str_split($string2);
        $firstLength = \count($first);
        $secondLength = \count($second);

        if ($firstLength === 0) {
            return $secondLength * $insertionCost;
        }
        if ($secondLength === 0) {
            return $firstLength * $deletionCost;
        }

        $previous = [];
        for ($j = 0; $j <= $secondLength; $j++) {
            $previous[$j] = $j * $insertionCost;
        }

        for ($i = 0; $i < $firstLength; $i++) {
            $current = [$previous[0] + $deletionCost];
            for ($j = 0; $j < $secondLength; $j++) {
                $cost = $previous[$j] + ($first[$i] === $second[$j] ? 0 : $replacementCost);
                $cost = min($cost, $previous[$j + 1] + $deletionCost, $current[$j] + $insertionCost);
                $current[$j + 1] = $cost;
            }
            $previous = $current;
        }

        return $previous[$secondLength];
    }

    /**
     * Wraps mb_strtolower (strtolower).
     */
    public static function lower(string $string): string
    {
        return mb_strtolower($string);
    }

    /**
     * Wraps ltrim (UTF-8 aware; ".." ranges are only supported for ASCII characters).
     */
    public static function ltrim(string $string, ?string $characters = null): string
    {
        return self::trimWith($string, $characters, true, false);
    }

    /**
     * Wraps md5.
     */
    public static function md5(string $string, bool $binary = false): string
    {
        return md5($string, $binary);
    }

    /**
     * Wraps metaphone (English, byte based).
     */
    public static function metaphone(string $string, int $maxPhonemes = 0): string
    {
        return metaphone($string, $maxPhonemes);
    }

    /**
     * Wraps nl2br.
     */
    public static function nl2br(string $string, bool $useXhtml = true): string
    {
        return nl2br($string, $useXhtml);
    }

    /**
     * Wraps mb_ord (ord).
     */
    public static function ord(string $character): int
    {
        return mb_ord($character);
    }

    /**
     * Wraps mb_str_pad (str_pad).
     */
    public static function pad(string $string, int $length, string $padString = ' ', int $type = STR_PAD_RIGHT): string
    {
        return mb_str_pad($string, $length, $padString, $type);
    }

    /**
     * Wraps str_getcsv.
     */
    public static function parseCsv(string $string, string $separator = ',', string $enclosure = '"', string $escape = ''): array
    {
        return str_getcsv($string, $separator, $enclosure, $escape);
    }

    /**
     * Wraps parse_str (returns the array).
     */
    public static function parseStr(string $string): array
    {
        parse_str($string, $result);

        return $result;
    }

    /**
     * Wraps strpbrk (null instead of false).
     */
    public static function pbrk(string $string, string $characters): ?string
    {
        $result = strpbrk($string, $characters);

        return $result === false ? null : $result;
    }

    /**
     * Wraps mb_strpos (strpos; null instead of false).
     */
    public static function pos(string $haystack, string $needle, int $offset = 0): ?int
    {
        $position = mb_strpos($haystack, $needle, $offset);

        return $position === false ? null : $position;
    }

    /**
     * Wraps quotemeta.
     */
    public static function quoteMeta(string $string): string
    {
        return quotemeta($string);
    }

    /**
     * Wraps random_bytes.
     */
    public static function randomBytes(int $length): string
    {
        return random_bytes($length);
    }

    /**
     * Wraps str_repeat.
     */
    public static function repeat(string $string, int $times): string
    {
        return str_repeat($string, $times);
    }

    /**
     * Wraps str_replace.
     */
    public static function replace(string|array $subject, string|array $search, string|array $replace): string|array
    {
        return str_replace($search, $replace, $subject);
    }

    /**
     * Wraps str_replace (returns [result, count]).
     */
    public static function replaceCount(string|array $subject, string|array $search, string|array $replace): array
    {
        $result = str_replace($search, $replace, $subject, $count);

        return [$result, $count];
    }

    /**
     * Wraps substr_replace (UTF-8 aware, single string form).
     */
    public static function replaceSubstr(string $string, string $replacement, int $offset, ?int $length = null): string
    {
        $total = mb_strlen($string);
        $start = $offset < 0 ? max(0, $total + $offset) : min($offset, $total);
        $length ??= $total;
        $end = $length < 0 ? max($start, $total + $length) : min($total, $start + $length);

        return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $end);
    }

    /**
     * Wraps strrev (UTF-8 aware).
     */
    public static function reverse(string $string): string
    {
        return implode('', array_reverse(mb_str_split($string)));
    }

    /**
     * Wraps str_rot13.
     */
    public static function rot13(string $string): string
    {
        return str_rot13($string);
    }

    /**
     * Wraps rtrim (UTF-8 aware; ".." ranges are only supported for ASCII characters).
     */
    public static function rtrim(string $string, ?string $characters = null): string
    {
        return self::trimWith($string, $characters, false, true);
    }

    /**
     * Wraps sscanf.
     */
    public static function scan(string $string, string $format): array|int|null
    {
        return sscanf($string, $format);
    }

    /**
     * Wraps sha1.
     */
    public static function sha1(string $string, bool $binary = false): string
    {
        return sha1($string, $binary);
    }

    /**
     * Wraps str_shuffle (UTF-8 aware).
     */
    public static function shuffle(string $string): string
    {
        $characters = mb_str_split($string);
        shuffle($characters);

        return implode('', $characters);
    }

    /**
     * Wraps similar_text (the percent output, returned as a float).
     */
    public static function similarity(string $string1, string $string2): float
    {
        $total = mb_strlen($string1) + mb_strlen($string2);

        if ($total === 0) {
            return 0.0;
        }

        return self::similarText($string1, $string2) * 2.0 * 100.0 / $total;
    }

    /**
     * Wraps similar_text (UTF-8 aware).
     */
    public static function similarText(string $string1, string $string2): int
    {
        return self::similarCharacters(mb_str_split($string1), mb_str_split($string2));
    }

    /**
     * Wraps soundex (English, byte based).
     */
    public static function soundex(string $string): string
    {
        return soundex($string);
    }

    /**
     * Wraps strspn (byte based).
     */
    public static function span(string $string, string $mask, int $offset = 0, ?int $length = null): int
    {
        return strspn($string, $mask, $offset, $length);
    }

    /**
     * Wraps mb_str_split (str_split).
     */
    public static function split(string $string, int $length = 1): array
    {
        return mb_str_split($string, $length);
    }

    /**
     * Wraps str_starts_with.
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    /**
     * Wraps mb_strstr (strstr, strchr; null instead of false).
     */
    public static function str(string $haystack, string $needle, bool $beforeNeedle = false): ?string
    {
        $result = mb_strstr($haystack, $needle, $beforeNeedle);

        return $result === false ? null : $result;
    }

    /**
     * Wraps stripcslashes.
     */
    public static function stripCSlashes(string $string): string
    {
        return stripcslashes($string);
    }

    /**
     * Wraps stripslashes.
     */
    public static function stripSlashes(string $string): string
    {
        return stripslashes($string);
    }

    /**
     * Wraps strip_tags.
     */
    public static function stripTags(string $string, array|string|null $allowedTags = null): string
    {
        return strip_tags($string, $allowedTags);
    }

    /**
     * Wraps mb_substr (substr).
     */
    public static function substr(string $string, int $offset, ?int $length = null): string
    {
        return mb_substr($string, $offset, $length);
    }

    /**
     * Wraps strtr.
     */
    public static function translate(string $string, string|array $from, ?string $to = null): string
    {
        return $to === null ? strtr($string, $from) : strtr($string, $from, $to);
    }

    /**
     * Wraps trim (UTF-8 aware; ".." ranges are only supported for ASCII characters).
     */
    public static function trim(string $string, ?string $characters = null): string
    {
        return self::trimWith($string, $characters, true, true);
    }

    /**
     * Wraps mb_strimwidth.
     */
    public static function trimWidth(string $string, int $start, int $width, string $trimMarker = ''): string
    {
        return mb_strimwidth($string, $start, $width, $trimMarker);
    }

    /**
     * Wraps ucfirst (UTF-8 aware).
     */
    public static function ucfirst(string $string): string
    {
        return $string === '' ? '' : mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    /**
     * Wraps ucwords (UTF-8 aware).
     */
    public static function ucwords(string $string, string $separators = " \t\r\n\f\v"): string
    {
        $result = '';
        $capitalize = true;
        foreach (mb_str_split($string) as $character) {
            $result .= $capitalize ? mb_strtoupper($character) : $character;
            $capitalize = str_contains($separators, $character);
        }

        return $result;
    }

    /**
     * Wraps mb_strtoupper (strtoupper).
     */
    public static function upper(string $string): string
    {
        return mb_strtoupper($string);
    }

    /**
     * Wraps mb_strwidth.
     */
    public static function width(string $string): int
    {
        return mb_strwidth($string);
    }

    /**
     * Wraps str_word_count (UTF-8 aware; positions are character offsets).
     */
    public static function wordCount(string $string, int $format = 0, ?string $characters = null): int|array
    {
        if ($format < 0 || $format > 2) {
            throw new \ValueError('Str::wordCount(): Argument #2 ($format) must be a valid format value');
        }

        $skipLeading = array_values(array_filter(["'", '-'], static fn(string $c): bool => $characters === null || !str_contains($characters, $c)));
        $offset = 0;
        if ($string !== '' && \in_array(mb_substr($string, 0, 1), $skipLeading, true)) {
            $string = mb_substr($string, 1);
            $offset = 1;
        }
        if (str_ends_with($string, '-') && ($characters === null || !str_contains($characters, '-'))) {
            $string = mb_substr($string, 0, -1);
        }

        $class = "[\\p{L}\\p{M}'\\-" . ($characters === null ? '' : preg_quote($characters, '/')) . ']+';
        preg_match_all('/' . $class . '/u', $string, $matches, PREG_OFFSET_CAPTURE);

        if ($format === 0) {
            return \count($matches[0]);
        }

        $words = [];
        foreach ($matches[0] as [$word, $byteOffset]) {
            $words[mb_strlen(substr($string, 0, $byteOffset)) + $offset] = $word;
        }

        return $format === 1 ? array_values($words) : $words;
    }

    /**
     * Wraps wordwrap (UTF-8 aware).
     */
    public static function wordwrap(string $string, int $width = 75, string $break = "\n", bool $cut = false): string
    {
        if ($break === '') {
            throw new \ValueError('Str::wordwrap(): Argument #3 ($break) cannot be empty');
        }
        if ($width === 0 && $cut) {
            throw new \ValueError('Str::wordwrap(): Argument #4 ($cut) cannot be true when argument #2 ($width) is 0');
        }
        if ($string === '') {
            return '';
        }

        $text = mb_str_split($string);
        $breakCharacters = mb_str_split($break);
        $breakLength = \count($breakCharacters);
        $textLength = \count($text);
        $result = '';
        $lastStart = 0;
        $lastSpace = 0;

        for ($current = 0; $current < $textLength; $current++) {
            if (
                $text[$current] === $breakCharacters[0]
                && $current + $breakLength < $textLength
                && \array_slice($text, $current, $breakLength) === $breakCharacters
            ) {
                $result .= implode('', \array_slice($text, $lastStart, $current - $lastStart + $breakLength));
                $current += $breakLength - 1;
                $lastStart = $lastSpace = $current + 1;
            } elseif ($text[$current] === ' ') {
                if ($current - $lastStart >= $width) {
                    $result .= implode('', \array_slice($text, $lastStart, $current - $lastStart)) . $break;
                    $lastStart = $current + 1;
                }
                $lastSpace = $current;
            } elseif ($current - $lastStart >= $width && $cut && $lastStart >= $lastSpace) {
                $result .= implode('', \array_slice($text, $lastStart, $current - $lastStart)) . $break;
                $lastStart = $lastSpace = $current;
            } elseif ($current - $lastStart >= $width && $lastStart < $lastSpace) {
                $result .= implode('', \array_slice($text, $lastStart, $lastSpace - $lastStart)) . $break;
                $lastStart = $lastSpace = $lastSpace + 1;
            }
        }

        if ($lastStart !== $current) {
            $result .= implode('', \array_slice($text, $lastStart, $current - $lastStart));
        }

        return $result;
    }

    private static function trimWith(string $string, ?string $characters, bool $left, bool $right): string
    {
        if ($characters === null || preg_match('/^[\x00-\x7F]*$/', $characters) === 1) {
            $characters ??= " \n\r\t\v\0";

            return match (true) {
                $left && $right => trim($string, $characters),
                $left => ltrim($string, $characters),
                default => rtrim($string, $characters),
            };
        }

        $class = '[' . preg_quote($characters, '/') . ']+';

        if ($left) {
            $string = (string) preg_replace('/^' . $class . '/u', '', $string);
        }

        if ($right) {
            $string = (string) preg_replace('/' . $class . '$/u', '', $string);
        }

        return $string;
    }

    /**
     * @param list<string> $first
     * @param list<string> $second
     */
    private static function similarCharacters(array $first, array $second): int
    {
        $firstLength = \count($first);
        $secondLength = \count($second);
        $max = 0;
        $firstPosition = 0;
        $secondPosition = 0;

        for ($p = 0; $p < $firstLength; $p++) {
            for ($q = 0; $q < $secondLength; $q++) {
                $length = 0;
                while ($p + $length < $firstLength && $q + $length < $secondLength && $first[$p + $length] === $second[$q + $length]) {
                    $length++;
                }
                if ($length > $max) {
                    $max = $length;
                    $firstPosition = $p;
                    $secondPosition = $q;
                }
            }
        }

        $sum = $max;
        if ($max > 0) {
            if ($firstPosition > 0 && $secondPosition > 0) {
                $sum += self::similarCharacters(\array_slice($first, 0, $firstPosition), \array_slice($second, 0, $secondPosition));
            }
            if ($firstPosition + $max < $firstLength && $secondPosition + $max < $secondLength) {
                $sum += self::similarCharacters(\array_slice($first, $firstPosition + $max), \array_slice($second, $secondPosition + $max));
            }
        }

        return $sum;
    }
}
