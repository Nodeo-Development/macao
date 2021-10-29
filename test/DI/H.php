<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Test\DI;

class H
{
    public I $i;

    /**
     * @param I $i
     */
    public function __construct(I $i)
    {
        $this->i = $i;
    }
}
