<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Response;

use JetBrains\PhpStorm\Pure;
use Macao\HTTP\Cookie\Collection\CookieCollection;
use Macao\HTTP\Header\Collection\HeaderCollection;
use Macao\HTTP\Response\Status\ResponseStatus;

/**
 * Interface Response
 *
 * This interface represents a response sent by the server to the client.
 *
 * A response is considered an immutable object.
 *
 * @see https://tools.ietf.org/html/rfc7231
 */
interface Response
{
    /**
     * Returns the status of the response.
     *
     * @return ResponseStatus The response status.
     */
    #[Pure]
    public function getStatus(): ResponseStatus;

    /**
     * Returns the headers of the response.
     *
     * @return HeaderCollection The response headers.
     */
    #[Pure]
    public function getHeaders(): HeaderCollection;

    /**
     * Returns the cookies of the response.
     *
     * @return CookieCollection The response cookies.
     */
    #[Pure]
    public function getCookies(): CookieCollection;

    /**
     * Sends the response to the client.
     */
    #[Pure]
    public function send();
}
