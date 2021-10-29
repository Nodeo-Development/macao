<?php

/*
 * Copyright (c) 2020, Juan (juan.valero@etu.univ-lyon1.fr), All rights reserved
 */

namespace Test\DI;

use Macao\DI\Attributes\Named;

class A implements IA
{
    private B $b;

    /**
     * @param B $b
     */
    public function __construct(#[Named("a")] B $b)
    {
        $this->b = $b;
    }

    public function sayHello(): string
    {
        return 'Hello !';
    }

    /**
     * @return B
     */
    public function getB(): B
    {
        return $this->b;
    }
}
