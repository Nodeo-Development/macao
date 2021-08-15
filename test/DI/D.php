<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved.
 * Unauthorized copying of this file, via any medium, is strictly prohibited.
 */

namespace Test\DI;

/**
 *
 */
class D
{
    private IA $a;
    private IC $c;

    /**
     * @param IA $a
     * @param IC $c
     */
    public function __construct(IA $a, IC $c)
    {
        $this->a = $a;
        $this->c = $c;
    }

    /**
     * @return IA
     */
    public function getA(): IA
    {
        return $this->a;
    }

    /**
     * @return IC
     */
    public function getC(): IC
    {
        return $this->c;
    }
}
