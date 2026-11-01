<?php

declare(strict_types=1);

namespace FG\Support\Strings\Traits;

use FG\Support\Strings\Char;

trait Charable
{
    /**
     * Iterates over the characters (not bytes) of the string.
     *
     * @return \Generator<int, Char>
     */
    public function getIterator(): \Generator
    {
        foreach (mb_str_split($this->toString()) as $char) {
            yield new Char($char);
        }
    }
}
