<?php

declare(strict_types=1);

namespace FG\Support\Strings\Traits;

trait Charable
{
    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        for($i = 0; $i < $this->length(); $i++)
        {
            yield $this->chartAt($i);
        }
    }
}