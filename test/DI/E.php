<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Test\DI;

class E implements IE
{
    private D $d;

    public function __construct(D $d)
    {
        $this->d = $d;
    }

    public function sayHi(): string
    {
        return 'Hi !';
    }

    /**
     * @return D
     */
    public function getD(): D
    {
        return $this->d;
    }
}
