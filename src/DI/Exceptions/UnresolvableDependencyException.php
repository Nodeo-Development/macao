<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Throwable;

class UnresolvableDependencyException extends RuntimeException
{
    /**
     * @param string $key
     * @param Throwable|null $previous
     */
    #[Pure]
    public function __construct(string $key, Throwable $previous = null)
    {
        parent::__construct(message:
            sprintf(
                'The key "%s" could not be resolved',
                $key
            ), previous: $previous);
    }
}
