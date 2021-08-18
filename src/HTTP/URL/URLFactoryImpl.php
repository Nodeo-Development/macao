<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\URL;

use InvalidArgumentException;
use Macao\Logger\Logger;

/**
 * Class URLFactoryImpl
 *
 * This class is the default implementation of {@link URLFactory}.
 */
class URLFactoryImpl implements URLFactory
{
    private const DEFAULT_SCHEME = 'https';
    private const DEFAULT_HOST = 'localhost';

    private Logger $logger;

    /**
     * URLFactoryImpl constructor.
     *
     * @param Logger $logger The application logger.
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function createUrl(string $url): URL
    {
        $url = utf8_encode($url);

        $components = parse_url($url);

        if ($components === false) {
            throw new InvalidArgumentException(
                'The specified URL is invalid'
            );
        }

        $scheme = $components['scheme'] ?? self::DEFAULT_SCHEME;
        $user = $components['user'] ?? '';

        if (isset($components['pass'])) {
            $this->logger->warn(
                'The specified URL contains a password component',
                'This feature is obsolete as it may pose a security risk',
                'The password will not be interpreted'
            );
        }

        $host = $components['host'] ?? self::DEFAULT_HOST;
        $port = $components['port'] ?? null;
        $path = $components['path'] ?? '';
        $query = $components['query'] ?? '';
        $fragment = $components['fragment'] ?? '';

        return new URLImpl(
            $scheme,
            $user,
            $host,
            $port,
            $path,
            $query,
            $fragment
        );
    }

    /**
     * @inheritDoc
     */
    public function createUrlFromGlobals(): URL
    {
        $scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';

        if (isset($_SERVER['HTTP_HOST'])) {
            [$host, $port] = explode($_SERVER['HTTP_HOST'], ':');
        } else {
            $host = $_SERVER['SERVER_NAME'];
            $port = $_SERVER['SERVER_PORT'];
        }

        ['path' => $path, 'query' => $query] = parse_url(
            $_SERVER['REQUEST_URI']
        );

        return new URLImpl(
            $scheme,
            '',
            $host,
            $port,
            $path,
            $query,
            ''
        );
    }
}
