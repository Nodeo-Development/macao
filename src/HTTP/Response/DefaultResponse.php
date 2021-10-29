<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Response;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use Macao\HTTP\Cookie\Collection\CookieCollection;
use Macao\HTTP\Cookie\Collection\CookieCollectionImpl;
use Macao\HTTP\Cookie\Cookie;
use Macao\HTTP\Header\Collection\HeaderCollection;
use Macao\HTTP\Header\Collection\HeaderCollectionImpl;
use Macao\HTTP\Header\Header;
use Macao\HTTP\Response\Status\ResponseStatus;
use Macao\HTTP\Response\Status\ResponseStatusImpl;
use Macao\IO\Stream;

/**
 * Class DefaultResponse
 *
 * This class represents a default response where no text is displayed to the
 * user.
 */
class DefaultResponse implements Response
{
    /**
     * The status of the response.
     *
     * @var ResponseStatus The response status.
     */
    private ResponseStatus $status;

    /**
     * The headers of the response.
     *
     * @var HeaderCollection The response headers.
     */
    private HeaderCollection $headers;

    /**
     * The cookies of the response.
     *
     * @var CookieCollection The response cookies.
     */
    private CookieCollection $cookies;

    /**
     * Response constructor.
     *
     * @param int $statusCode [optional] The response status code.
     * If the status code is not specified, it will default to
     * <code>Response::HTTP_OK</code>.
     * @param string|null $reasonPhrase [optional] The response reason phrase.
     * If the reason phrase is not specified or is null, it will default to
     * the recommended reason phrase for the response status code.
     * @param Stream|null $body [optional] The response body, if any.
     * @param string $protocolVersion [optional] The response protocol version.
     * If the protocol version is not specified, it will default to
     * <code>'1.1'</code>.
     * @param array $headers [optional] The response headers, as an associative
     * array of headers where each key is the header name and each value is the
     * header instance, if any.
     * @param array $cookies [optional] The response cookies, as an associative array
     * of cookies where each key is the cookie name and each value is the
     * cookie instance, if any.
     */
    public function __construct(
        #[ExpectedValues(valuesFromClass: Response::class)]
        int $statusCode = ResponseStatus::HTTP_OK,
        string $reasonPhrase = null,
        Stream $body = null,
        string $protocolVersion = '1.1',
        array $headers = [],
        array $cookies = []
    ) {
        //parent::__construct($body, $protocolVersion);

        $this->setStatus($statusCode, $reasonPhrase);

        $this->setHeaders($headers);
        $this->setCookies($cookies);
    }

    /**
     * Changes the status of the response.
     *
     * @param int $code The response status code.
     * @param string|null $reasonPhrase The response reason phrase.
     * If the reason phrase is not specified, is null or is an empty string, it
     * will default to the recommended reason phrase for the response status
     * code.
     */
    private function setStatus(
        #[ExpectedValues(valuesFromClass: ResponseStatus::class)] int $code,
        ?string $reasonPhrase = null
    ) {
        $this->status = new ResponseStatusImpl($code, $reasonPhrase);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getStatus(): ResponseStatus
    {
        return $this->status;
    }

    /**
     * Changes the headers of the response.
     *
     * @param Header[] $headers The response headers, as an associative array
     * of headers where each key is the header name and each value is the
     * header instance.
     */
    private function setHeaders(array $headers)
    {
        $this->headers = new HeaderCollectionImpl($headers);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getHeaders(): HeaderCollection
    {
        return $this->headers;
    }

    /**
     * Changes the headers of the response.
     *
     * @param Cookie[] $cookies The response cookies, as an associative array
     * of cookies where each key is the cookie name and each value is the
     * cookie instance.
     */
    private function setCookies(array $cookies)
    {
        $this->cookies = new CookieCollectionImpl($cookies);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getCookies(): CookieCollection
    {
        return $this->cookies;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function send()
    {
        foreach ($this->getHeaders() as $header) {
            header($header->__toString());
        }

        foreach ($this->getCookies() as $cookie) {
            setcookie($cookie->getName(), $cookie->getValue(), secure: true);
        }
    }
}
