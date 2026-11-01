<?php

declare(strict_types=1);

namespace FG\Support\Exceptions;

/**
 * Thrown when code tries to modify an immutable object in place.
 */
class ImmutableException extends \LogicException {}
