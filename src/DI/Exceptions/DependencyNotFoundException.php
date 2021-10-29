<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

class DependencyNotFoundException extends RuntimeException
{
    /**
     * @param string $key
     */
    #[Pure]
    public function __construct(string $key)
    {
        parent::__construct(
            sprintf(
                'The key "%s" could not be found',
                $key
            )
        );
    }
}
