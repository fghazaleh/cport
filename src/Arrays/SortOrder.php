<?php

declare(strict_types=1);

namespace FG\Support\Arrays;

enum SortOrder: string
{
    /** Sort ascending. */
    case Asc = 'ASC';

    /** Sort descending. */
    case Desc = 'DESC';

    /** Sort using a "natural order" algorithm. */
    case Natural = 'NAT';
}
