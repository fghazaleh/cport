<?php

declare(strict_types=1);

namespace FG\Support;

use FG\Support\Exceptions\InvalidArgumentException;

final class StrTest extends WrapperTestCase
{
    public function testAddCSlashes(): void
    {
        $a = ['foo[bar]', 'A..Z'];
        self::assertSameResult(addcslashes($a[0], $a[1]), Str::addCSlashes(...$a));
        $a = ['zoo', 'o'];
        self::assertSameResult('z\\o\\o', Str::addCSlashes(...$a));
    }

    public function testAddSlashes(): void
    {
        $a = ["O'Reilly \"x\""];
        self::assertSameResult(addslashes($a[0]), Str::addSlashes(...$a));
    }

    public function testBinToHex(): void
    {
        $a = ['abc'];
        self::assertSameResult('616263', Str::binToHex(...$a));
        $a = ['é'];
        self::assertSameResult('c3a9', Str::binToHex(...$a));
    }

    public function testByteLength(): void
    {
        $a = ['héllo'];
        self::assertSameResult(6, Str::byteLength(...$a));
    }

    public function testChr(): void
    {
        $a = [65];
        self::assertSameResult('A', Str::chr(...$a));
        $a = [233];
        self::assertSameResult('é', Str::chr(...$a));
    }

    public function testChrThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Str::chr(...[-1]);
    }

    public function testChunkSplit(): void
    {
        $a = ['abcdefg',3,'-'];
        self::assertSameResult(chunk_split('abcdefg', 3, '-'), Str::chunkSplit(...$a));
        $a = [''];
        self::assertSameResult(chunk_split(''), Str::chunkSplit(...$a));
        $a = ['éàü',1,'|'];
        self::assertSameResult('é|à|ü|', Str::chunkSplit(...$a));
    }

    public function testCompare(): void
    {
        $a = ['a','b'];
        self::assertSameResult(-1, Str::compare(...$a));
        $a = ['b','a'];
        self::assertSameResult(1, Str::compare(...$a));
        $a = ['a','a'];
        self::assertSameResult(0, Str::compare(...$a));
    }

    public function testCompareFirst(): void
    {
        $a = ['abcd','abxx',2];
        self::assertSameResult(0, Str::compareFirst(...$a));
        $a = ['abcd','abxx',3];
        self::assertSameResult(-1, Str::compareFirst(...$a));
    }

    public function testCompareFirstIgnoreCase(): void
    {
        $a = ['ABcd','abXX',2];
        self::assertSameResult(0, Str::compareFirstIgnoreCase(...$a));
        $a = ['Éa','éb',1];
        self::assertSameResult(0, Str::compareFirstIgnoreCase(...$a));
    }

    public function testCompareIgnoreCase(): void
    {
        $a = ['HELLO','hello'];
        self::assertSameResult(0, Str::compareIgnoreCase(...$a));
        $a = ['É','é'];
        self::assertSameResult(0, Str::compareIgnoreCase(...$a));
        $a = ['a','B'];
        self::assertSameResult(-1, Str::compareIgnoreCase(...$a));
    }

    public function testCompareLocale(): void
    {
        $a = ['a','b'];
        self::assertSameResult(-1, Str::compareLocale(...$a));
        $a = ['a','a'];
        self::assertSameResult(0, Str::compareLocale(...$a));
    }

    public function testCompareNatural(): void
    {
        $a = ['img12','img10'];
        self::assertSameResult(1, Str::compareNatural(...$a));
        $a = ['img2','img10'];
        self::assertSameResult(-1, Str::compareNatural(...$a));
        $a = ['a','a'];
        self::assertSameResult(0, Str::compareNatural(...$a));
    }

    public function testCompareNaturalIgnoreCase(): void
    {
        $a = ['IMG2','img10'];
        self::assertSameResult(-1, Str::compareNaturalIgnoreCase(...$a));
        $a = ['A1','a1'];
        self::assertSameResult(0, Str::compareNaturalIgnoreCase(...$a));
    }

    public function testCompareSubstr(): void
    {
        $a = ['abcde','cd',2,2];
        self::assertSameResult(0, Str::compareSubstr(...$a));
        $a = ['abcde','CD',2,2,true];
        self::assertSameResult(0, Str::compareSubstr(...$a));
        $a = ['abcde','xx',0];
        self::assertSameResult(substr_compare('abcde', 'xx', 0) <=> 0, Str::compareSubstr(...$a));
        $a = ['héllo','llo',2];
        self::assertSameResult(0, Str::compareSubstr(...$a));
    }

    public function testCompareSubstrThrows(): void
    {
        $this->expectException(\ValueError::class);
        Str::compareSubstr(...['abc','a',9]);
    }

    public function testContains(): void
    {
        $a = ['héllo','él'];
        self::assertSameResult(true, Str::contains(...$a));
        $a = ['abc','z'];
        self::assertSameResult(false, Str::contains(...$a));
        $a = ['abc',''];
        self::assertSameResult(true, Str::contains(...$a));
    }

    public function testConvertCase(): void
    {
        $a = ['hello wörld'];
        self::assertSameResult('Hello Wörld', Str::convertCase(...$a));
        $a = ['Hello', MB_CASE_UPPER];
        self::assertSameResult('HELLO', Str::convertCase(...$a));
    }

    public function testConvertEncoding(): void
    {
        $a = ['é','ISO-8859-1','UTF-8'];
        self::assertSameResult(mb_convert_encoding('é', 'ISO-8859-1', 'UTF-8'), Str::convertEncoding(...$a));
    }

    public function testCount(): void
    {
        $a = ['hello hello', 'll'];
        self::assertSameResult(2, Str::count(...$a));
        $a = ['héhé', 'é'];
        self::assertSameResult(2, Str::count(...$a));
    }

    public function testCountChars(): void
    {
        $a = ['abca', 1];
        self::assertSameResult(count_chars('abca', 1), Str::countChars(...$a));
        $a = ['abca', 3];
        self::assertSameResult('abc', Str::countChars(...$a));
    }

    public function testCrc32(): void
    {
        $a = ['hello'];
        self::assertSameResult(crc32('hello'), Str::crc32(...$a));
    }

    public function testCspan(): void
    {
        $a = ['abcd','cd'];
        self::assertSameResult(2, Str::cspan(...$a));
    }

    public function testDecrement(): void
    {
        $a = ['b'];
        self::assertSameResult('a', Str::decrement(...$a));
        $a = ['Ba'];
        self::assertSameResult('Az', Str::decrement(...$a));
    }

    public function testDetectEncoding(): void
    {
        $a = ['abc',['ASCII','UTF-8']];
        self::assertSameResult('ASCII', Str::detectEncoding(...$a));
        $a = ["\xff",['UTF-8'],true];
        self::assertSameResult(null, Str::detectEncoding(...$a));
    }

    public function testEndsWith(): void
    {
        $a = ['héllo','llo'];
        self::assertSameResult(true, Str::endsWith(...$a));
        $a = ['abc','b'];
        self::assertSameResult(false, Str::endsWith(...$a));
    }

    public function testExplode(): void
    {
        $a = ['a,b,c', ','];
        self::assertSameResult(['a','b','c'], Str::explode(...$a));
        $a = ['a,b,c', ',', 2];
        self::assertSameResult(['a','b,c'], Str::explode(...$a));
    }

    public function testExplodeThrows(): void
    {
        $this->expectException(\ValueError::class);
        Str::explode(...['a', '']);
    }

    public function testFormat(): void
    {
        $a = ['%05.1f|%s', 3.14159, 'x'];
        self::assertSameResult(\sprintf('%05.1f|%s', 3.14159, 'x'), Str::format(...$a));
    }

    public function testFormatArray(): void
    {
        $a = ['%d-%s', [5,'a']];
        self::assertSameResult('5-a', Str::formatArray(...$a));
    }

    public function testHexToBin(): void
    {
        $a = ['616263'];
        self::assertSameResult('abc', Str::hexToBin(...$a));
    }

    public function testHexToBinThrows1(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Str::hexToBin(...['abc']);
    }

    public function testHexToBinThrows2(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Str::hexToBin(...['zz']);
    }

    public function testHtmlEntities(): void
    {
        $a = ['café <b>'];
        self::assertSameResult('caf&eacute; &lt;b&gt;', Str::htmlEntities(...$a));
    }

    public function testHtmlEntityDecode(): void
    {
        $a = ['caf&eacute; &lt;'];
        self::assertSameResult('café <', Str::htmlEntityDecode(...$a));
    }

    public function testHtmlSpecialChars(): void
    {
        $a = ['<a href="x">T&C</a>'];
        self::assertSameResult(htmlspecialchars($a[0]), Str::htmlSpecialChars(...$a));
        $a = ['&amp; &', ENT_QUOTES, null, false];
        self::assertSameResult('&amp; &amp;', Str::htmlSpecialChars(...$a));
    }

    public function testHtmlSpecialCharsDecode(): void
    {
        $a = ['&lt;b&gt; &amp; &quot;x&quot;'];
        self::assertSameResult('<b> & "x"', Str::htmlSpecialCharsDecode(...$a));
    }

    public function testIncrement(): void
    {
        $a = ['a'];
        self::assertSameResult('b', Str::increment(...$a));
        $a = ['Az'];
        self::assertSameResult('Ba', Str::increment(...$a));
        $a = ['zz'];
        self::assertSameResult('aaa', Str::increment(...$a));
    }

    public function testIpos(): void
    {
        $a = ['HÉLLO','é'];
        self::assertSameResult(1, Str::ipos(...$a));
        $a = ['abc','B'];
        self::assertSameResult(1, Str::ipos(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::ipos(...$a));
    }

    public function testIreplace(): void
    {
        $a = ['HELLO','l','L'];
        self::assertSameResult('HELLO', Str::ireplace(...$a));
        $a = ['Hello','L','x'];
        self::assertSameResult('Hexxo', Str::ireplace(...$a));
    }

    public function testIreplaceCount(): void
    {
        $a = ['Hello','L','x'];
        self::assertSameResult(['Hexxo',2], Str::ireplaceCount(...$a));
    }

    public function testIstr(): void
    {
        $a = ['USER@host','r@'];
        self::assertSameResult('R@host', Str::istr(...$a));
        $a = ['abc','Z'];
        self::assertSameResult(null, Str::istr(...$a));
    }

    public function testIsValidEncoding(): void
    {
        $a = ['é'];
        self::assertSameResult(true, Str::isValidEncoding(...$a));
        $a = ["\xff"];
        self::assertSameResult(false, Str::isValidEncoding(...$a));
    }

    public function testLastIpos(): void
    {
        $a = ['ABCabc','B'];
        self::assertSameResult(4, Str::lastIpos(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::lastIpos(...$a));
    }

    public function testLastIstr(): void
    {
        $a = ['A/B/C','/'];
        self::assertSameResult('/C', Str::lastIstr(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::lastIstr(...$a));
    }

    public function testLastPos(): void
    {
        $a = ['abcabc','b'];
        self::assertSameResult(4, Str::lastPos(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::lastPos(...$a));
    }

    public function testLastStr(): void
    {
        $a = ['a/b/c','/'];
        self::assertSameResult('/c', Str::lastStr(...$a));
        $a = ['a/b/c','/',true];
        self::assertSameResult('a/b', Str::lastStr(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::lastStr(...$a));
    }

    public function testLcfirst(): void
    {
        $a = ['Élan'];
        self::assertSameResult('élan', Str::lcfirst(...$a));
        $a = ['Hello'];
        self::assertSameResult(lcfirst($a[0]), Str::lcfirst(...$a));
        $a = [''];
        self::assertSameResult('', Str::lcfirst(...$a));
    }

    public function testLength(): void
    {
        $a = ['héllo'];
        self::assertSameResult(5, Str::length(...$a));
        $a = [''];
        self::assertSameResult(0, Str::length(...$a));
    }

    public function testLevenshtein(): void
    {
        $a = ['kitten','sitting'];
        self::assertSameResult(3, Str::levenshtein(...$a));
        $a = ['','abc'];
        self::assertSameResult(3, Str::levenshtein(...$a));
        $a = ['abc',''];
        self::assertSameResult(3, Str::levenshtein(...$a));
        $a = ['same','same'];
        self::assertSameResult(0, Str::levenshtein(...$a));
        $a = ['é','e'];
        self::assertSameResult(1, Str::levenshtein(...$a));
        $a = ['kitten','sitting',1,2,3];
        self::assertSameResult(5, Str::levenshtein(...$a));
    }

    public function testLower(): void
    {
        $a = ['HÉLLO'];
        self::assertSameResult('héllo', Str::lower(...$a));
        $a = ['ABC'];
        self::assertSameResult(strtolower($a[0]), Str::lower(...$a));
    }

    public function testLtrim(): void
    {
        $a = ["  hi \n"];
        self::assertSameResult(ltrim($a[0]), Str::ltrim(...$a));
        $a = ['xxhixx', 'x'];
        self::assertSameResult(ltrim($a[0], $a[1]), Str::ltrim(...$a));
        $a = ['«hi»', '«»'];
        self::assertSameResult('hi»', Str::ltrim(...$a));
    }

    public function testMd5(): void
    {
        $a = ['hello'];
        self::assertSameResult(md5('hello'), Str::md5(...$a));
        $a = ['hello', true];
        self::assertSameResult(md5('hello', true), Str::md5(...$a));
    }

    public function testMetaphone(): void
    {
        $a = ['Thompson'];
        self::assertSameResult(metaphone('Thompson'), Str::metaphone(...$a));
    }

    public function testNl2br(): void
    {
        $a = ["a\nb"];
        self::assertSameResult(nl2br($a[0]), Str::nl2br(...$a));
        $a = ["a\nb", false];
        self::assertSameResult(nl2br($a[0], false), Str::nl2br(...$a));
    }

    public function testOrd(): void
    {
        $a = ['A'];
        self::assertSameResult(65, Str::ord(...$a));
        $a = ['é'];
        self::assertSameResult(233, Str::ord(...$a));
    }

    public function testPad(): void
    {
        $a = ['é',5,'-',STR_PAD_BOTH];
        self::assertSameResult('--é--', Str::pad(...$a));
        $a = ['5',3,'0',STR_PAD_LEFT];
        self::assertSameResult('005', Str::pad(...$a));
        $a = ['ab',5];
        self::assertSameResult(str_pad('ab', 5), Str::pad(...$a));
        $a = ['abc',2];
        self::assertSameResult('abc', Str::pad(...$a));
    }

    public function testParseCsv(): void
    {
        $a = ['a,b,"c d"'];
        self::assertSameResult(['a','b','c d'], Str::parseCsv(...$a));
    }

    public function testParseStr(): void
    {
        $a = ['a=1&b[]=2&b[]=3'];
        self::assertSameResult(['a' => '1','b' => ['2','3']], Str::parseStr(...$a));
    }

    public function testPbrk(): void
    {
        $a = ['This is a test','st'];
        self::assertSameResult('s is a test', Str::pbrk(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::pbrk(...$a));
    }

    public function testPos(): void
    {
        $a = ['héllo wörld','w'];
        self::assertSameResult(6, Str::pos(...$a));
        $a = ['abcabc','b',2];
        self::assertSameResult(4, Str::pos(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::pos(...$a));
    }

    public function testQuoteMeta(): void
    {
        $a = ['1+1=2?'];
        self::assertSameResult(quotemeta($a[0]), Str::quoteMeta(...$a));
    }

    public function testRandomBytes(): void
    {
        $a = [8];
        $this->assertSame(8, \strlen(Str::randomBytes(...$a)));
    }

    public function testRepeat(): void
    {
        $a = ['ab',3];
        self::assertSameResult('ababab', Str::repeat(...$a));
        $a = ['é',2];
        self::assertSameResult('éé', Str::repeat(...$a));
    }

    public function testReplace(): void
    {
        $a = ['Hello World','World','PHP'];
        self::assertSameResult(str_replace('World', 'PHP', 'Hello World'), Str::replace(...$a));
        $a = ['abc',['a','b'],['1','2']];
        self::assertSameResult('12c', Str::replace(...$a));
        $a = [['a','b'],'a','x'];
        self::assertSameResult(['x','b'], Str::replace(...$a));
    }

    public function testReplaceCount(): void
    {
        $a = ['aaa','a','b'];
        self::assertSameResult(['bbb',3], Str::replaceCount(...$a));
    }

    public function testReplaceSubstr(): void
    {
        $a = ['Hello','J',0,1];
        self::assertSameResult(substr_replace('Hello', 'J', 0, 1), Str::replaceSubstr(...$a));
        $a = ['abcdef','X',1,-2];
        self::assertSameResult(substr_replace('abcdef', 'X', 1, -2), Str::replaceSubstr(...$a));
        $a = ['abcdef','X',-2];
        self::assertSameResult(substr_replace('abcdef', 'X', -2), Str::replaceSubstr(...$a));
        $a = ['abcdef','X',2,0];
        self::assertSameResult(substr_replace('abcdef', 'X', 2, 0), Str::replaceSubstr(...$a));
        $a = ['héllo','E',1,1];
        self::assertSameResult('hEllo', Str::replaceSubstr(...$a));
    }

    public function testReverse(): void
    {
        $a = ['héllo'];
        self::assertSameResult('olléh', Str::reverse(...$a));
        $a = ['abc'];
        self::assertSameResult(strrev('abc'), Str::reverse(...$a));
    }

    public function testRot13(): void
    {
        $a = ['Hello'];
        self::assertSameResult('Uryyb', Str::rot13(...$a));
    }

    public function testRtrim(): void
    {
        $a = ["  hi \n"];
        self::assertSameResult(rtrim($a[0]), Str::rtrim(...$a));
        $a = ['xxhixx', 'x'];
        self::assertSameResult(rtrim($a[0], $a[1]), Str::rtrim(...$a));
        $a = ['«hi»', '«»'];
        self::assertSameResult('«hi', Str::rtrim(...$a));
    }

    public function testScan(): void
    {
        $a = ['age: 25 name: Bob','age: %d name: %s'];
        self::assertSameResult(sscanf($a[0], $a[1]), Str::scan(...$a));
    }

    public function testSha1(): void
    {
        $a = ['hello'];
        self::assertSameResult(sha1('hello'), Str::sha1(...$a));
    }

    public function testShuffle(): void
    {
        $a = ['héllo wörld'];
        $this->assertEqualsCanonicalizing(mb_str_split($a[0]), mb_str_split(Str::shuffle(...$a)));
    }

    public function testSimilarity(): void
    {
        $a = ['World','Word'];
        self::assertSameResult((static function (string $x, string $y): float {
            similar_text($x, $y, $p);
            return $p;
        })('World', 'Word'), Str::similarity(...$a));
        $a = ['',''];
        self::assertSameResult(0.0, Str::similarity(...$a));
    }

    public function testSimilarText(): void
    {
        $a = ['World','Word'];
        self::assertSameResult(similar_text('World', 'Word'), Str::similarText(...$a));
        $a = ['Hello','World'];
        self::assertSameResult(similar_text('Hello', 'World'), Str::similarText(...$a));
        $a = ['',''];
        self::assertSameResult(0, Str::similarText(...$a));
        $a = ['é','é'];
        self::assertSameResult(1, Str::similarText(...$a));
    }

    public function testSoundex(): void
    {
        $a = ['Robert'];
        self::assertSameResult(soundex('Robert'), Str::soundex(...$a));
    }

    public function testSpan(): void
    {
        $a = ['42 apples','0123456789'];
        self::assertSameResult(2, Str::span(...$a));
    }

    public function testSplit(): void
    {
        $a = ['héllo'];
        self::assertSameResult(['h','é','l','l','o'], Str::split(...$a));
        $a = ['abcdef',4];
        self::assertSameResult(['abcd','ef'], Str::split(...$a));
    }

    public function testStartsWith(): void
    {
        $a = ['héllo','hé'];
        self::assertSameResult(true, Str::startsWith(...$a));
        $a = ['abc','b'];
        self::assertSameResult(false, Str::startsWith(...$a));
    }

    public function testStr(): void
    {
        $a = ['user@host','@'];
        self::assertSameResult('@host', Str::str(...$a));
        $a = ['user@host','@',true];
        self::assertSameResult('user', Str::str(...$a));
        $a = ['abc','z'];
        self::assertSameResult(null, Str::str(...$a));
    }

    public function testStripCSlashes(): void
    {
        $a = ['a\nb'];
        self::assertSameResult(stripcslashes($a[0]), Str::stripCSlashes(...$a));
    }

    public function testStripSlashes(): void
    {
        $a = ['O\\\'Reilly'];
        self::assertSameResult(stripslashes($a[0]), Str::stripSlashes(...$a));
    }

    public function testStripTags(): void
    {
        $a = ['<b>Hi</b> <i>x</i>'];
        self::assertSameResult(strip_tags($a[0]), Str::stripTags(...$a));
        $a = ['<b>Hi</b> <i>x</i>', '<b>'];
        self::assertSameResult(strip_tags($a[0], '<b>'), Str::stripTags(...$a));
    }

    public function testSubstr(): void
    {
        $a = ['héllo',1,3];
        self::assertSameResult('éll', Str::substr(...$a));
        $a = ['héllo',-2];
        self::assertSameResult('lo', Str::substr(...$a));
        $a = ['hello',1,3];
        self::assertSameResult(substr('hello', 1, 3), Str::substr(...$a));
    }

    public function testTranslate(): void
    {
        $a = ['Hi all','ai','eo'];
        self::assertSameResult(strtr('Hi all', 'ai', 'eo'), Str::translate(...$a));
        $a = ['Hi all',['Hi' => 'Hello','all' => 'world']];
        self::assertSameResult('Hello world', Str::translate(...$a));
    }

    public function testTrim(): void
    {
        $a = ["  hi \n"];
        self::assertSameResult(trim($a[0]), Str::trim(...$a));
        $a = ['xxhixx', 'x'];
        self::assertSameResult(trim($a[0], $a[1]), Str::trim(...$a));
        $a = ['«hi»', '«»'];
        self::assertSameResult('hi', Str::trim(...$a));
    }

    public function testTrimWidth(): void
    {
        $a = ['Hello World', 0, 10, '...'];
        self::assertSameResult('Hello W...', Str::trimWidth(...$a));
    }

    public function testUcfirst(): void
    {
        $a = ['élan'];
        self::assertSameResult('Élan', Str::ucfirst(...$a));
        $a = ['hello'];
        self::assertSameResult(ucfirst($a[0]), Str::ucfirst(...$a));
        $a = [''];
        self::assertSameResult('', Str::ucfirst(...$a));
    }

    public function testUcwords(): void
    {
        $a = ['hello wörld'];
        self::assertSameResult('Hello Wörld', Str::ucwords(...$a));
        $a = ["hello\tbig world"];
        self::assertSameResult(ucwords($a[0]), Str::ucwords(...$a));
        $a = ['hello-world foo', '-'];
        self::assertSameResult('Hello-World foo', Str::ucwords(...$a));
    }

    public function testUpper(): void
    {
        $a = ['Héllo'];
        self::assertSameResult('HÉLLO', Str::upper(...$a));
        $a = ['abc'];
        self::assertSameResult(strtoupper($a[0]), Str::upper(...$a));
    }

    public function testWidth(): void
    {
        $a = ['abc'];
        self::assertSameResult(3, Str::width(...$a));
        $a = ['日本'];
        self::assertSameResult(4, Str::width(...$a));
    }

    public function testWordCount(): void
    {
        $a = ["Hello fri3nd, you're looking good today!"];
        self::assertSameResult(str_word_count($a[0]), Str::wordCount(...$a));
        $a = ["Hello fri3nd, you're looking good today!", 1];
        self::assertSameResult(str_word_count($a[0], 1), Str::wordCount(...$a));
        $a = ["Hello fri3nd, you're looking good today!", 2];
        self::assertSameResult(str_word_count($a[0], 2), Str::wordCount(...$a));
        $a = ['Hello fri3nd', 1, '3'];
        self::assertSameResult(str_word_count($a[0], 1, '3'), Str::wordCount(...$a));
        $a = ['-foo bar-', 1];
        self::assertSameResult(str_word_count($a[0], 1), Str::wordCount(...$a));
        $a = ['héllo wörld', 1];
        self::assertSameResult(['héllo','wörld'], Str::wordCount(...$a));
        $a = ['héllo wörld', 2];
        self::assertSameResult([0 => 'héllo',6 => 'wörld'], Str::wordCount(...$a));
    }

    public function testWordCountThrows(): void
    {
        $this->expectException(\ValueError::class);
        Str::wordCount(...['a', 5]);
    }

    public function testWordwrap(): void
    {
        $a = ['The quick brown fox sat over the lazy dog', 15, "\n", true];
        self::assertSameResult(wordwrap($a[0], $a[1], $a[2], $a[3]), Str::wordwrap(...$a));
        $a = ['A very long woooooooooooord.', 8, "\n", true];
        self::assertSameResult(wordwrap($a[0], $a[1], $a[2], $a[3]), Str::wordwrap(...$a));
        $a = ['A very long woooooooooooord.', 8, "\n", false];
        self::assertSameResult(wordwrap($a[0], $a[1], $a[2], $a[3]), Str::wordwrap(...$a));
        $a = ['The quick brown fox', 10, "<br>\n"];
        self::assertSameResult(wordwrap($a[0], $a[1], $a[2]), Str::wordwrap(...$a));
        $a = ["a b\nc d", 3];
        self::assertSameResult(wordwrap($a[0], $a[1]), Str::wordwrap(...$a));
        $a = ['The quick brown fox sat over the lazy dog'];
        self::assertSameResult(wordwrap($a[0]), Str::wordwrap(...$a));
        $a = ['héllo wörld ünï', 5, "\n", true];
        self::assertSameResult("héllo\nwörld\nünï", Str::wordwrap(...$a));
    }

    public function testWordwrapThrows1(): void
    {
        $this->expectException(\ValueError::class);
        Str::wordwrap(...['abc', 5, '']);
    }

    public function testWordwrapThrows2(): void
    {
        $this->expectException(\ValueError::class);
        Str::wordwrap(...['abc', 0, "\n", true]);
    }
}
