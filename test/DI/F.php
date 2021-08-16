<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Test\DI;

class F
{
    private string $DB_HOST;

    public function __construct(string $DB_HOST)
    {
        $this->DB_HOST = $DB_HOST;
    }

    /**
     * @return string
     */
    public function getDBHOST(): string
    {
        return $this->DB_HOST;
    }
}
