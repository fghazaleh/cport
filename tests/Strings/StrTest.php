<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use FG\Support\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testingCreateFromStatic(): void
    {
        $str = Str::fromString('Franco Ghazaleh');

        $this->assertInstanceOf(Str::class, $str);
    }

    public function testingTraversable(): void
    {
        $str = Str::fromString('Franco');

        foreach ($str as $char) {
            $this->assertInstanceOf(Char::class, $char);
        }

        $this->assertSame('o', $char->toString());
    }

    public function testingSubString(): void
    {
        $str = new Str('Franco Ghazaleh');

        $result = $str->subString(4)->toString();

        $this->assertSame('co Ghazaleh', $result);
    }

    public function testingTrim(): void
    {
        $str = new Str('  Franco  ');
        $this->assertSame('Franco', (string) $str->trim());

        $str = new Str(' Franco ');
        $this->assertSame(' Franco', (string) $str->rtrim());

        $str = new Str(' Franco ');
        $this->assertSame('Franco ', (string) $str->ltrim());
    }

    public function testingStringLength(): void
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertSame(15, $str->length());
    }

    public function testingStringUpperCaseAndLowerCase(): void
    {
        $str = new Str('Franco');

        $this->assertSame('FRANCO', $str->toUpper()->toString());
        $this->assertSame('franco', $str->toLower()->toString());
    }

    public function testingStringLowerCaseFirst(): void
    {
        $str = new Str('Hello World');

        $this->assertSame('hello World', $str->toLowerFirst()->toString());
    }

    public function testingStringUpperCaseFirst(): void
    {
        $str = new Str('hello world');

        $this->assertSame('Hello World', $str->toUpperFirst()->toString());
    }

    public function testingStringReplace(): void
    {
        $str = new Str('Franco Ghazaleh');

        $result = $str->replace('Gh', '3h');

        $this->assertSame('Franco 3hazaleh', $result->toString());
    }

    public function testingStringStudly(): void
    {
        $str = new Str('build_email_list');

        $result = $str->studly();

        $this->assertSame('BuildEmailList', $result->toString());
    }

    public function testingStringCamel(): void
    {
        $str = new Str('build_email_list');

        $result = $str->camel();

        $this->assertSame('buildEmailList', $result->toString());
    }

    public function testingStringAfterAndBefore(): void
    {
        $str = new Str('Franco');

        $this->assertSame('Franco Ghazaleh', $str->appendAfter(' Ghazaleh')->toString());

        $this->assertSame('Awesome Franco', $str->appendBefore('Awesome ')->toString());
    }

    public function testingStringReverse(): void
    {
        $str = new Str('Franco');

        $this->assertSame('ocnarF', $str->reverse()->toString());

    }

    public function testingCharAt(): void
    {
        $str = new Str('Franco Ghazaleh');
        $this->assertSame('o', $str->charAt(5)->toString());
    }

    public function testingCharCodeAt(): void
    {
        $str = new Str('Franco Ghazaleh');
        $this->assertSame(99, $str->charCodeAt(4));
    }

    public function testingIndexOf(): void
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertSame(7, $str->indexOf('G'));

        $this->assertSame(-1, $str->indexOf('GG'));
    }

    public function testingLastIndexOf(): void
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertSame(11, $str->lastIndexOf('a'));

        $this->assertSame(-1, $str->lastIndexOf('GG'));
    }

    public function testingStartsWithAndEndsWith(): void
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertTrue($str->startsWith('F'));
        $this->assertFalse($str->startsWith('a'));

        $this->assertTrue($str->endsWith('h'));
        $this->assertFalse($str->endsWith('z'));
    }

    public function testingStripTags(): void
    {
        $str = new Str('<h1><b>Franco Ghazaleh</b></h1>');

        $this->assertSame('Franco Ghazaleh', $str->stripTags()->toString());
        $this->assertSame('<b>Franco Ghazaleh</b>', $str->stripTags('<b>')->toString());
    }

    public function testingStringMd5(): void
    {
        $str = new Str('Franco');

        $this->assertSame('99d2470a3073b4a570031f75896c6ac6', $str->toMd5());
        $this->assertSame('6868e1bb931f8e534691c34dd5077fd4', $str->toMd5('some-key'));
    }

    public function testingContains(): void
    {
        $str = new Str('My name is Franco Gh');

        $this->assertTrue($str->contains('Franco'));
        $this->assertFalse($str->contains('franco'));
    }

    public function testingAny(): void
    {
        $str = new Str('My name is Franco Gh');

        $this->assertTrue($str->any('FRANCO'));
        $this->assertTrue($str->any('franco'));
    }

    public function testingParseQueryString(): void
    {
        $str = Str::fromString('first=value&arr[]=foo+bar&arr[]=baz');

        $result = $str->parseQueryString();

        $this->assertIsArray($result->all());
        $this->assertCount(2, $result);
        $this->assertSame('value', $result['first']);
    }

    public function testingPad(): void
    {
        $str = Str::fromString('Franco');

        $value = $str->pad(10)->toString();

        $this->assertSame('  Franco  ', $value);
    }

    public function testingMultibyteLengthAndCase(): void
    {
        $str = new Str('Élan àéî');

        $this->assertSame(8, $str->length());
        $this->assertSame('ÉLAN ÀÉÎ', $str->toUpper()->toString());
        $this->assertSame('élan àéî', $str->toLower()->toString());
        $this->assertSame('élan àéî', $str->toLowerFirst()->toString());
        $this->assertSame('Élan Àéî', (new Str('élan àéî'))->toUpperFirst()->toString());
    }

    public function testingMultibyteSubStringReverseAndSearch(): void
    {
        $str = new Str('héllo wörld');

        $this->assertSame('éllo', $str->subString(1, 4)->toString());
        $this->assertSame('dlröw olléh', $str->reverse()->toString());
        $this->assertSame(6, $str->indexOf('w'));
        $this->assertSame(1, $str->indexOf('é'));
        $this->assertSame(8, $str->lastIndexOf('r'));
        $this->assertTrue($str->any('WÖR'));
    }

    public function testingMultibytePad(): void
    {
        $this->assertSame('--é--', (new Str('é'))->pad(5, '-')->toString());
    }

    public function testingIteratingYieldsCharacters(): void
    {
        $chars = [];
        foreach (new Str('aé') as $char) {
            $chars[] = $char->toString();
        }

        $this->assertSame(['a', 'é'], $chars);
    }

    public function testingCharAtAndCodeAtAreMultibyteSafe(): void
    {
        $str = new Str('aé');

        $this->assertSame('é', $str->charAt(1)->toString());
        $this->assertSame(233, $str->charCodeAt(1));
    }

    public function testingIsEmpty(): void
    {
        $this->assertTrue((new Str())->isEmpty());
        $this->assertTrue((new Str('a'))->isNotEmpty());
    }

    public function testingExplode(): void
    {
        $this->assertSame(['a', 'b', 'c'], (new Str('a,b,c'))->explode(',')->all());
        $this->assertSame(['a', 'b,c'], (new Str('a,b,c'))->explode(',', 2)->all());
    }

    public function testingExplodeWithEmptyDelimiterThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Str('abc'))->explode('');
    }

    public function testingRepeat(): void
    {
        $this->assertSame('ababab', (new Str('ab'))->repeat(3)->toString());
        $this->assertSame('', (new Str('ab'))->repeat(0)->toString());
    }

    public function testingRepeatNegativeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Str('ab'))->repeat(-1);
    }

    public function testingLimit(): void
    {
        $this->assertSame('The quick...', (new Str('The quick brown fox'))->limit(10)->toString());
        $this->assertSame('short', (new Str('short'))->limit(10)->toString());
        $this->assertSame('héllo>', (new Str('héllo wörld'))->limit(5, '>')->toString());
    }

    public function testingTitleAndWordCount(): void
    {
        $this->assertSame('Hello World', (new Str('hello WORLD'))->title()->toString());
        $this->assertSame(3, (new Str('  one two   three '))->wordCount());
        $this->assertSame(0, (new Str('   '))->wordCount());
    }

    public function testingSnakeAndKebab(): void
    {
        $this->assertSame('foo_bar_baz', (new Str('fooBar baz'))->snake()->toString());
        $this->assertSame('foo_bar_baz', (new Str('FooBar-Baz'))->snake()->toString());
        $this->assertSame('foo-bar-baz', (new Str('foo_bar baz'))->kebab()->toString());
        $this->assertSame('foo-bar', (new Str('fooBar'))->kebab()->toString());
    }

    public function testingSlug(): void
    {
        $this->assertSame('hello-world', (new Str('  Hello, World! '))->slug()->toString());
        $this->assertSame('hello_world', (new Str('Hello World'))->slug('_')->toString());
        $this->assertSame('héllo-wörld', (new Str('Héllo Wörld'))->slug()->toString());
    }

    public function testingBeforeAfterBetween(): void
    {
        $str = new Str('[name](value)(more)');

        $this->assertSame('[name]', $str->before('(')->toString());
        $this->assertSame('value)(more)', $str->after('(')->toString());
        $this->assertSame('name', $str->between('[', ']')->toString());
        $this->assertSame('value)(more', $str->between('(', ')')->toString());
        $this->assertSame('[name](value)', $str->beforeLast('(')->toString());
        $this->assertSame($str->toString(), $str->before('#')->toString());
        $this->assertSame($str->toString(), $str->after('#')->toString());
    }

    public function testingStringsAreImmutable(): void
    {
        $str = new Str('abc');
        $str->toUpper()->reverse()->appendAfter('x');

        $this->assertSame('abc', $str->toString());
    }
}
