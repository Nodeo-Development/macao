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
 * Represents a loader of a configuration file which provides properties
 * to create a logger.
 */
interface Loader
{
    /**
     * Creates an array of the logger properties.
     *
     * @return array An associative array of the logger properties.
     *               It can contain the following items :
     *               <ul>
     *               <li>file(string) : the logger filename</li>
     *               <li>format(string) [optional] : the logger format</li>
     *               <li>dateFormat(string) [optional] : the logger date format</li>
     *               </ul>
     */
    #[Pure]
    #[ArrayShape([
        'file'       => 'string',
        'format'     => 'string',
        'dateFormat' => 'string',
    ])]
    public function load(): array;
}