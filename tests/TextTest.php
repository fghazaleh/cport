<?php

declare(strict_types=1);

namespace FG\Support;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TextTest extends TestCase
{
    public function testCreationAndConversion(): void
    {
        $this->assertSame('', Text::of()->toString());
        $this->assertSame('abc', (string) Text::of('abc'));
        $this->assertSame('7', Text::of(Number::of(7))->toString(), 'any Stringable works');
        $this->assertSame('"abc"', json_encode(Text::of('abc')));
    }

    public function testInspection(): void
    {
        $text = Text::of('héllo');

        $this->assertSame(5, $text->length());
        $this->assertTrue(Text::of()->isEmpty());
        $this->assertTrue($text->isNotEmpty());
        $this->assertTrue($text->equals('héllo'));
        $this->assertFalse($text->equals('HÉLLO'));
        $this->assertTrue($text->equals('HÉLLO', true));
        $this->assertTrue($text->contains('él'));
        $this->assertTrue($text->startsWith('hé'));
        $this->assertTrue($text->endsWith('llo'));
        $this->assertSame(3, Text::of('one two  three')->wordCount());
    }

    public function testTextIsImmutable(): void
    {
        $text = Text::of('abc');
        $text->upper()->reverse()->append('x');

        $this->assertSame('abc', $text->toString());
    }

    public function testCase(): void
    {
        $this->assertSame('ÉLAN', Text::of('élan')->upper()->toString());
        $this->assertSame('élan', Text::of('ÉLAN')->lower()->toString());
        $this->assertSame('Élan', Text::of('élan')->ucfirst()->toString());
        $this->assertSame('éLAN', Text::of('ÉLAN')->lcfirst()->toString());
        $this->assertSame('Hello World', Text::of('hello WORLD')->title()->toString());
    }

    #[DataProvider('caseConversions')]
    public function testWordCaseConversions(string $input, string $camel, string $studly, string $snake, string $kebab): void
    {
        $text = Text::of($input);

        $this->assertSame($camel, $text->camel()->toString());
        $this->assertSame($studly, $text->studly()->toString());
        $this->assertSame($snake, $text->snake()->toString());
        $this->assertSame($kebab, $text->kebab()->toString());
    }

    /**
     * @return array<string, array{string, string, string, string, string}>
     */
    public static function caseConversions(): array
    {
        return [
            'camel' => ['fooBarBaz', 'fooBarBaz', 'FooBarBaz', 'foo_bar_baz', 'foo-bar-baz'],
            'snake' => ['foo_bar_baz', 'fooBarBaz', 'FooBarBaz', 'foo_bar_baz', 'foo-bar-baz'],
            'kebab' => ['foo-bar-baz', 'fooBarBaz', 'FooBarBaz', 'foo_bar_baz', 'foo-bar-baz'],
            'spaces' => ['Foo bar  baz', 'fooBarBaz', 'FooBarBaz', 'foo_bar_baz', 'foo-bar-baz'],
            'acronym' => ['HTTPServer', 'httpServer', 'HttpServer', 'http_server', 'http-server'],
            'digits' => ['version2Beta', 'version2Beta', 'Version2Beta', 'version2_beta', 'version2-beta'],
            'unicode' => ['élanVital', 'élanVital', 'ÉlanVital', 'élan_vital', 'élan-vital'],
            'empty' => ['', '', '', '', ''],
        ];
    }

    public function testSnakeWithCustomSeparator(): void
    {
        $this->assertSame('foo.bar', Text::of('fooBar')->snake('.')->toString());
    }

    public function testSlug(): void
    {
        $this->assertSame('hello-world', Text::of('  Hello, World! ')->slug()->toString());
        $this->assertSame('hello_world', Text::of('Hello World')->slug('_')->toString());
        $this->assertSame('héllo-wörld', Text::of('Héllo Wörld')->slug()->toString());
    }

    public function testTrimAndPad(): void
    {
        $this->assertSame('hi', Text::of("  hi \n")->trim()->toString());
        $this->assertSame('hi ', Text::of(' hi ')->ltrim()->toString());
        $this->assertSame(' hi', Text::of(' hi ')->rtrim()->toString());
        $this->assertSame('hi', Text::of('xxhixx')->trim('x')->toString());
        $this->assertSame('--é--', Text::of('é')->pad(5, '-', STR_PAD_BOTH)->toString());
    }

    public function testComposing(): void
    {
        $this->assertSame('abcd', Text::of('ab')->append('c', Text::of('d'))->toString());
        $this->assertSame('cdab', Text::of('ab')->prepend('c', 'd')->toString());
        $this->assertSame('ababab', Text::of('ab')->repeat(3)->toString());
        $this->assertSame('olléh', Text::of('héllo')->reverse()->toString());
        $this->assertSame('Hello PHP', Text::of('Hello World')->replace('World', 'PHP')->toString());
        $this->assertSame('12c', Text::of('abc')->replace(['a', 'b'], ['1', '2'])->toString());
        $this->assertSame('Hello', Text::of('Hello World')->remove(' World')->toString());
        $this->assertSame('éll', Text::of('héllo')->substr(1, 3)->toString());
    }

    public function testLimit(): void
    {
        $this->assertSame('The quick...', Text::of('The quick brown fox')->limit(9)->toString());
        $this->assertSame('short', Text::of('short')->limit(10)->toString());
        $this->assertSame('héllo>', Text::of('héllo wörld')->limit(5, '>')->toString());
    }

    public function testBeforeAfterBetween(): void
    {
        $text = Text::of('[name](value)(more)');

        $this->assertSame('[name]', $text->before('(')->toString());
        $this->assertSame('value)(more)', $text->after('(')->toString());
        $this->assertSame('[name](value)', $text->beforeLast('(')->toString());
        $this->assertSame('name', $text->between('[', ']')->toString());
        $this->assertSame('value)(more', $text->between('(', ')')->toString());
        $this->assertSame($text->toString(), $text->before('#')->toString());
        $this->assertSame($text->toString(), $text->after('#')->toString());
        $this->assertSame($text->toString(), $text->before('')->toString());
        $this->assertSame($text->toString(), $text->between('', ']')->toString());
    }

    public function testExplodeAndWordsReturnPlainArrays(): void
    {
        $this->assertSame(['a', 'b', 'c'], Text::of('a,b,c')->explode(','));
        $this->assertSame(['a', 'b,c'], Text::of('a,b,c')->explode(',', 2));
        $this->assertSame(['héllo', 'wörld'], Text::of('héllo wörld')->words());
    }
}
