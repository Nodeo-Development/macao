<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Macao\Logger;

/**
 * Represents a logger instance.
 */
interface Logger
{
    /**
     * Logs the specified message at the specified level.
     *
     * @param string   $message The log message.
     * @param LogLevel $level   The log level.
     * @param int|null $time    The log time.
     *
     * @return void
     * @see sprintf()
     */
    public function log(
        string $message,
        LogLevel $level,
        ?int $time = null
    ): void;
}
