<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use PHPUnit\Framework\TestCase;

class CharTest extends TestCase
{
    public function testingCreateCharFromStatic(): void
    {
        $char = Char::fromCode(90);

        $this->assertInstanceOf(Char::class, $char);
        $this->assertSame('Z', $char->toString());
    }

    public function testingToHexMethod(): void
    {
        $char = new Char('a');

        $this->assertSame('61', $char->toHex());
    }

    public function testingMultibyteCode(): void
    {
        $char = new Char('é');

        $this->assertSame(233, $char->code());
        $this->assertSame('é', Char::fromCode(233)->toString());
        $this->assertSame('c3a9', $char->toHex());
    }

    public function testingCastToString(): void
    {
        $this->assertSame('x', (string) new Char('x'));
    }
}
