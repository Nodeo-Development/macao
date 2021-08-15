<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Definition;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

class ClassDefinition extends Definition
{
    public const RE_INSTANTIABLE = 0;
    public const SINGLETON = 1;

    private int $status;

    #[Pure]
    public function __construct(
        mixed $value,
        #[ExpectedValues([
            self::RE_INSTANTIABLE,
            self::SINGLETON
        ])] int $status = self::RE_INSTANTIABLE
    ) {
        parent::__construct($value);

        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
