<?php

declare(strict_types=1);

namespace FG\Support\Strings;

use PHPUnit\Framework\TestCase;

class CharTest extends TestCase
{
    /**
     * @test
     */
    public function testingCreateCharFromStatic()
    {
        $char = Char::fromCode(90);

        $this->assertInstanceOf(Char::class, $char);
        $this->assertSame('Z', $char->toString());
    }
}