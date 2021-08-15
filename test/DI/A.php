<?php

/*
 * Copyright (c) 2020, Juan (juan.valero@etu.univ-lyon1.fr), All rights reserved
 */

namespace Test\DI;

class A implements IA
{
    private B $b;

    /**
     * @param B $b
     */
    public function __construct(B $b)
    {
        $this->b = $b;
    }

    public function sayHello(): string
    {
        return 'Hello from A !';
    }

    /**
     * @return B
     */
    public function getB(): B
    {
        return $this->b;
    }
}
