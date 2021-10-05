<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    /**
     * @test
     */
    public function testingCreateFromStatic()
    {
        $str = Str::fromString('Franco Ghazaleh');

        $this->assertInstanceOf(Str::class, $str);
    }

    /**
     * @test
     */
    public function testingTraversable()
    {
        $str = Str::fromString('Franco');

        foreach ($str as $char) {
            $this->assertInstanceOf(Char::class, $char);
        }

        $this->assertSame('o', $char->toString());
    }

    /**
     * @test
     */
    public function testingSubString()
    {
        $str = new Str('Franco Ghazaleh');

        $result = $str->subString(4)->toString();

        $this->assertSame('co Ghazaleh', $result);
    }

    /**
     * @test
     */
    public function testingTrim()
    {
        $str = new Str('  Franco  ');
        $this->assertSame('Franco', (string)$str->trim());

        $str = new Str(' Franco ');
        $this->assertSame(' Franco', (string)$str->rtrim());

        $str = new Str(' Franco ');
        $this->assertSame('Franco ', (string)$str->ltrim());
    }

    /**
     * @test
     */
    public function testingStringLength()
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertSame(15, $str->length());
    }

    /**
     * @test
     */
    public function testingStringUpperCaseAndLowerCase()
    {
        $str = new Str('Franco');

        $this->assertSame('FRANCO', $str->toUpper()->toString());
        $this->assertSame('franco', $str->toLower()->toString());
    }

    /**
     * @test
     */
    public function testingStringLowerCaseFirst()
    {
        $str = new Str('Hello World');

        $this->assertSame('hello World', $str->toLowerFirst()->toString());
    }

    /**
     * @test
     */
    public function testingStringUpperCaseFirst()
    {
        $str = new Str('hello world');

        $this->assertSame('Hello World', $str->toUpperFirst()->toString());
    }

    /**
     * @test
     */
    public function testingStringReplace()
    {
        $str = new Str('Franco Ghazaleh');

        $result = $str->replace('Gh', '3h');

        $this->assertSame('Franco 3hazaleh', $result->toString());
    }

    /**
     * @test
     */
    public function testingStringStudly()
    {
        $str = new Str('build_email_list');

        $result = $str->studly();

        $this->assertSame('BuildEmailList', $result->toString());
    }

    /**
     * @test
     */
    public function testingStringCamel()
    {
        $str = new Str('build_email_list');

        $result = $str->camel();

        $this->assertSame('buildEmailList', $result->toString());
    }

    /**
     * @test
     */
    public function testingStringAfterAndBefore()
    {
        $str = new Str('Franco');

        $this->assertSame('Franco Ghazaleh', $str->appendAfter(' Ghazaleh')->toString());

        $this->assertSame('Awesome Franco', $str->appendBefore('Awesome ')->toString());
    }

    /**
     * @test
     */
    public function testingStringReverse()
    {
        $str = new Str('Franco');

        $this->assertSame('ocnarF', $str->reverse()->toString());

    }

    /**
     * @test
     */
    public function testingCharAt()
    {
        $str = new Str('Franco Ghazaleh');
        $this->assertSame('o', $str->charAt(5)->toString());
    }

    /**
     * @test
     */
    public function testingCharCodeAt()
    {
        $str = new Str('Franco Ghazaleh');
        $this->assertSame(99, $str->charCodeAt(4));
    }

    /**
     * @test
     */
    public function testingIndexOf()
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertSame(7, $str->indexOf('G'));

        $this->assertSame(-1, $str->indexOf('GG'));
    }

    /**
     * @test
     */
    public function testingLastIndexOf()
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertSame(11, $str->lastIndexOf('a'));

        $this->assertSame(-1, $str->lastIndexOf('GG'));
    }

    /**
     * @test
     */
    public function testingStartsWithAndEndsWith()
    {
        $str = new Str('Franco Ghazaleh');

        $this->assertTrue($str->startsWith('F'));
        $this->assertFalse($str->startsWith('a'));

        $this->assertTrue($str->endsWith('h'));
        $this->assertFalse($str->endsWith('z'));
    }

    /**
     * @test
     */
    public function testingStripTags()
    {
        $str = new Str('<h1><b>Franco Ghazaleh</b></h1>');

        $this->assertSame('Franco Ghazaleh', $str->stripTags()->toString());
        $this->assertSame('<b>Franco Ghazaleh</b>', $str->stripTags('<b>')->toString());
    }

    /**
     * @test
     */
    public function testingStringMd5()
    {
        $str = new Str('Franco');

        $this->assertSame('99d2470a3073b4a570031f75896c6ac6', $str->toMd5());
        $this->assertSame('6868e1bb931f8e534691c34dd5077fd4', $str->toMd5('some-key'));
    }
}
