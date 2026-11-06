<?php

declare(strict_types=1);

namespace FG\Support;

use PHPUnit\Framework\TestCase;

abstract class WrapperTestCase extends TestCase
{
    /**
     * Strict comparison, except that finite floats are compared with a tiny
     * tolerance so tests do not depend on the platform's libm rounding.
     */
    protected static function assertSameResult(mixed $expected, mixed $actual): void
    {
        if (\is_float($expected) && \is_float($actual) && is_finite($expected)) {
            self::assertEqualsWithDelta($expected, $actual, 1e-9);

            return;
        }

        self::assertSame($expected, $actual);
    }
}
