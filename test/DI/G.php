<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Test\DI;

use Macao\DI\Attributes\Named;

class G
{
    private string $databaseHost;

    /**
     * @param string $databaseHost
     */
    public function __construct(#[Named("DB_HOST")] string $databaseHost)
    {
        $this->databaseHost = $databaseHost;
    }

    /**
     * @return string
     */
    public function getDatabaseHost(): string
    {
        return $this->databaseHost;
    }
}
