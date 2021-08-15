<?php

/*
 * Copyright (c) 2020, Juan (juan.valero@etu.univ-lyon1.fr), All rights reserved
 */

namespace Test\DI;

class C implements IC
{
    private IA $a;

    /**
     * @param IA $a
     */
    public function __construct(IA $a)
    {
        $this->a = $a;
        $this->a->sayHello();
    }
}
