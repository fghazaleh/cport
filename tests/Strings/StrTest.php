<?php

declare(strict_types=1);

namespace FG\Support\Strings;

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
        $this->assertSame('Franco', (string)$str->trim());

        $str = new Str(' Franco ');
        $this->assertSame(' Franco', (string)$str->rtrim());

        $str = new Str(' Franco ');
        $this->assertSame('Franco ', (string)$str->ltrim());
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
        $str = Str::fromString("first=value&arr[]=foo+bar&arr[]=baz");

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
}
