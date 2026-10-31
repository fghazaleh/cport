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
}
