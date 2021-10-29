<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP;

use JetBrains\PhpStorm\Pure;
use Macao\IO\Stream;

/**
 * Interface Message
 *
 * This interface represents a message sent through the HTTP protocol.
 * It can be sent from the client to the server ({@link Request})
 * or vice versa ({@link Response}).
 *
 * A message is considered an immutable object.
 *
 * Please report to the RFC 7230 specification for further details about
 * messages.
 *
 * @see https://tools.ietf.org/html/rfc7230
 */
interface Message
{
    /**
     * Returns the body of the message.
     *
     * @return Stream|null The message body, or null if the body is
     * empty.
     */
    #[Pure]
    public function getBody(): ?Stream;

    /**
     * Returns the version of the message HTTP protocol.
     *
     * @return string The message HTTP protocol version.
     */
    #[Pure]
    public function getProtocolVersion(): string;
}
