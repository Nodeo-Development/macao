<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Response;

use InvalidArgumentException;
use Macao\HTTP\Cookie\Cookie;
use Macao\HTTP\URL\URL;

class RedirectResponseBuilder
{
    /**
     * The cookies of the response.
     *
     * @var Cookie[] The response cookies.
     */
    private array $cookies;

    /**
     * The requested URL of the response.
     *
     * @var URL|null The response requested URL, or null if no requested URL
     * was specified.
     */
    private ?URL $url;

    /**
     * The requested countdown of the response.
     *
     * @var int|null The time in seconds after which the user will be
     * redirected, or null of no requested countdown was specified.
     */
    private ?int $countdown;

    public function withCookie(Cookie $cookie): self
    {
        $name = $cookie->getName();

        if (array_key_exists($name, $this->cookies)) {
            throw new InvalidArgumentException(
                sprintf(
                    'A cookie with the name "%s" is already registered',
                    $name
                )
            );
        }

        return $this;
    }

    public function withoutCookie(string $name): self
    {
        if (!array_key_exists($name, $this->cookies)) {
            throw new InvalidArgumentException(
                sprintf(
                    'No cookie with the name "%s" is registered',
                    $name
                )
            );
        }

        unset($this->cookies[$name]);

        return $this;
    }

    public function withURL(URL $url)
    {
        $this->url = $url;
    }

    public function withCountdown(int $countdown)
    {
        if ($countdown < 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'The redirection countdown must be strictly positive.' .
                    'Given : %s',
                    $countdown
                )
            );
        }

        $this->countdown = $countdown;
    }
}
