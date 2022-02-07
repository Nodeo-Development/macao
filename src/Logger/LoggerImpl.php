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
 * Default implementation of {@link Logger}.
 */
class LoggerImpl implements Logger
{
    /**
     * @var resource The log file
     */
    private $logFile;

    /**
     * @var LogFormat The log format
     */
    private readonly LogFormat $format;

    /**
     * LoggerImpl constructor.
     *
     * @param resource  $logFile The log file.
     * @param LogFormat $format  The log format.
     */
    public function __construct($logFile, LogFormat $format)
    {
        $this->logFile = $logFile;
        $this->format = $format;
    }

    /**
     * LoggerImpl destructor.
     */
    public function __destruct()
    {
        fclose($this->logFile);
    }

    /**
     * @inheritDoc
     */
    public function log(
        string $message,
        LogLevel $level,
        ?int $time = null
    ): void {
        if (is_null($time)) {
            $time = time();
        }

        $message = $this->format->format($message, $level, $time);

        fwrite($this->logFile, $message);
    }
}
