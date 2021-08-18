<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Cookie;

/**
 * Interface Cookie
 *
 * TODO
 */
interface Cookie
{
    /**
     * Returns the name of the cookie.
     *
     * @return string The cookie name.
     */
    public function getName(): string;

    /**
     * Returns the value of the cookie.
     *
     * @return string The cookie value.
     */
    public function getValue(): string;
}
