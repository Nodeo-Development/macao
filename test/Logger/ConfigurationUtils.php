<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Test\Logger;

use JetBrains\PhpStorm\Pure;

class ConfigurationUtils
{
    #[Pure]
    private static function getConfigurationFolderPath(): string
    {
        return __DIR__
            .DIRECTORY_SEPARATOR
            .'Configuration'
            .DIRECTORY_SEPARATOR;
    }

    #[Pure]
    public static function getConfigurationFilePath(string $filename): string
    {
        return self::getConfigurationFolderPath().$filename;
    }
}