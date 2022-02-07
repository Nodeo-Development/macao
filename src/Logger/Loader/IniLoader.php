<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Macao\Logger\Loader;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Represents the loader for .ini configuration files.
 */
class IniLoader implements Loader
{
    private const LOGGER_SECTION = "logger";

    /**
     * @var string The configuration filename.
     */
    private readonly string $fileName;

    /**
     * IniLoader.
     *
     * @param string $filename The configuration filename.
     */
    public function __construct(string $filename)
    {
        $this->fileName = $filename;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    #[ArrayShape([
        'file' => 'string',
        'format'  => 'string',
        'dateFormat' => 'string'
    ])]
    public function load(): array
    {
        $ini = parse_ini_file($this->fileName, true);

        if (array_key_exists(self::LOGGER_SECTION, $ini)) {
            // Multiple sections, we only take what interests us
            $ini = $ini[self::LOGGER_SECTION];
        }

        return $ini;
    }
}