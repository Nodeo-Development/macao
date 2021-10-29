<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved.
 * Unauthorized copying of this file, via any medium, is strictly prohibited.
 */

namespace Test\DI;

use JetBrains\PhpStorm\Pure;
use Macao\DI\AbstractBinder;
use Macao\DI\Scope;

class MockBinder extends AbstractBinder
{
    private B $b;

    #[Pure]
    public function __construct(B $b = null)
    {
        if (is_null($b)) {
            $this->b = new B();
        } else {
            $this->b = $b;
        }
    }

    public function configure(): void
    {
        $this->bindScalar("DB_HOST", "localhost");
        $this->bindScalar("DB_PORT", 3309);

        $this->bind(IA::class, A::class, Scope::SINGLETON);

        $this->bindInstance(B::class, $this->b);

        $this->bind(IC::class, C::class);

        $this->bind(IE::class, E::class);
    }
}
