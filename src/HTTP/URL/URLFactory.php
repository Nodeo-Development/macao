<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\URL;

/**
 * Interface URLFactory
 *
 * This interface contains various methods to create a {@link URL} instance.
 */
interface URLFactory
{
    /**
     * Creates a {@link URL} with the specified URL.
     *
     * @param string $url The URL.
     * @return URL The created {@link URL} instance
     * with the specified URL.
     */
    public function createUrl(string $url): URL;

    /**
     * Creates a {@link URL} with the contents of the $_SERVER global.
     *
     * @return URL The created {@link URL} instance
     * with the $_SERVER global contents.
     */
    public function createUrlFromGlobals(): URL;
}
