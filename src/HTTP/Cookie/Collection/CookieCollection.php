<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Cookie\Collection;

use Iterator;
use Macao\HTTP\Cookie\Cookie;

/**
 * Interface CookieCollection
 *
 * TODO
 */
interface CookieCollection extends Iterator
{
    /**
     * Checks if the collection contains the specified cookie.
     *
     * @param string $name The cookie name.
     * @return bool <code>true</code> if the collection contains the
     * specified cookie, <code>false</code> otherwise.
     */
    public function hasCookie(string $name): bool;

    /**
     * Returns the cookie with the specified name.
     *
     * @param string $name The cookie name.
     * @return Cookie The cookie with the specified name.
     */
    public function getCookie(string $name): Cookie;

    /**
     * Returns all the cookies from the collection.
     *
     * @return Cookie[] An associative array where each key is a cookie name
     * and each value is the {@link Cookie} instance of this name.
     */
    public function getCookies(): array;
}
