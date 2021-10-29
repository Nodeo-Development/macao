<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Response;

use InvalidArgumentException;
use JetBrains\PhpStorm\ExpectedValues;
use Macao\HTTP\Cookie\Cookie;
use Macao\HTTP\Response\Status\ResponseStatus;

class DefaultResponseBuilder
{
    /**
     * The status code of the response.
     *
     * @var int The response status code.
     */
    private int $statusCode;

    /**
     * The reason phrase of the response.
     *
     * @var string The response reason phrase.
     */
    private string $reasonPhrase;

    /**
     * The cookies of the response.
     *
     * @var Cookie[] The response cookies, as an associative array
     * of cookies where each key is the cookie name and each value is the
     * cookie instance.
     */
    private array $cookies;

    public function withStatusCode(
        #[ExpectedValues(valuesFromClass: ResponseStatus::class)] int $statusCode
    ) {
        if (
            !array_key_exists(
                $statusCode,
                ResponseStatus::DEFAULT_STATUS_PHRASES
            )
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is not a valid status code.' .
                    ' Please refer to the Response interface for a list of allowed status codes',
                    $statusCode
                )
            );
        }

        $this->statusCode = $statusCode;
    }

    public function withReasonPhrase(string $reasonPhrase)
    {
        $this->reasonPhrase = $reasonPhrase;
    }

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

    public function build(): Response
    {
        if (!isset($this->statusCode)) {
            $this->statusCode = ResponseStatus::HTTP_OK;
        }

        if (!isset($this->reasonPhrase) || empty($this->reasonPhrase)) {
            $this->reasonPhrase = null;
        }

        if (!isset($this->cookies)) {
            $this->cookies = [];
        }

        return new DefaultResponse(
            $this->statusCode,
            $this->reasonPhrase,
            null,
            '1.1',
            $this->cookies,
            []
        );
    }
}
