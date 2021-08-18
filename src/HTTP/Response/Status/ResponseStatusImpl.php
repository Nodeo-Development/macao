<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Response\Status;

use InvalidArgumentException;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use Macao\HTTP\Response\Response;

/**
 * Class ResponseStatusImpl
 *
 * This class is the default implementation of {@link ResponseStatus}.
 */
class ResponseStatusImpl implements ResponseStatus
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
     * ResponseStatusImpl constructor.
     *
     * @param int|null $statusCode [optional] The response status code.
     * If the status code is not specified or is null, it will default to
     * <code>Response::HTTP_OK</code>.
     * Please report to {@link ResponseStatus} for a list of the allowed
     * status codes.
     * @param string|null $reasonPhrase [optional] The response reason phrase.
     * If the reason phrase is not specified or is null, it will default to
     * the recommended reason phrase for the response status code.
     * Please report to {@link ResponseStatus} for a list of the recommended
     * reason phrases.
     */
    public function __construct(
        #[ExpectedValues(valuesFromClass: Response::class)]
        ?int $statusCode = self::HTTP_OK,
        ?string $reasonPhrase = null
    ) {
        $this->setStatusCode($statusCode);
        $this->setReasonPhrase($reasonPhrase);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Changes the status code of the response.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @param int|null $statusCode The response status code.
     * If the status code is null, it will default to
     * <code>Response::HTTP_OK</code>.
     * Please report to {@link ResponseStatus} for a list of the allowed
     * status codes.
     */
    private function setStatusCode(
        #[ExpectedValues(valuesFromClass: ResponseStatus::class)]
        ?int $statusCode
    ) {
        if (!array_key_exists($statusCode, self::DEFAULT_STATUS_PHRASES)) {
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

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * Changes the reason phrase of the response.
     *
     * @param string|null $reasonPhrase [optional] The response reason phrase.
     * If the reason phrase is null or is an empty string, it will default to
     * the recommended reason phrase for the response status code.
     * Please report to {@link ResponseStatus} for a list of the recommended
     * reason phrases.
     */
    private function setReasonPhrase(?string $reasonPhrase = null)
    {
        if (empty($reasonPhrase)) {
            $this->reasonPhrase = self::DEFAULT_STATUS_PHRASES[$this->statusCode];
        } else {
            $this->reasonPhrase = $reasonPhrase;
        }
    }
}
