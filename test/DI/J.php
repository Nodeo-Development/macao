<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Test\DI;

class J
{
    public H $h; // Circular dependency

    /**
     * @param H $h
     */
    public function __construct(H $h)
    {
        $this->h = $h;
    }
}
